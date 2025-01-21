<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, CheckIcon, XMarkIcon, PlusIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
    const router = useRouter();
	const preview =  ref();
    const po_det = ref(false)
	const success =  ref()
	const error =  ref()
	const save_button =  ref()
	const print_button =  ref(false)
	const hide_printButton = ref()
	const successAlert = ref(false)
	const hideAlert = ref(true)
    const warningAlert = ref(false)
    const dangerAlert = ref(false)
    const dangerAlerterrors = ref(false)
    const dangerAlert_payment = ref(false)
    const successAlertCD = ref(false)
    let po_list=ref([]);
    let pr_head=ref([]);
    let po_head=ref([]);
    let po_details=ref([]);
    let signatories=ref([]);
    let payment_list=ref([]);
    let rfd_head=ref([]);
    let rfd_payments=ref([]);
    let vendor=ref([]);
    let po_id=ref(0);
    let total_per_item=ref(0);
    let grand_total=ref(0);
    let company=ref('');
    let pay_to=ref('');
    let check_name=ref('');
    let rfd_no=ref('');
    let rfd_date=ref('');
    let due_date=ref('');
    let check_due=ref('');
    let payment_option=ref(0);
    let bank_no=ref('');
    let notes=ref('');
    let prepared_by=ref('');
    let checked_by=ref(0);
    let noted_by=ref(0);
    let endorsed_by=ref(0);
    let approved_by=ref(0);
    let received_by=ref(0);
    const formatter = new Intl.NumberFormat('en-US', { 
        minimumFractionDigits: 4 
    });
    let payment_description = ref('')
    let payment_amount = ref(0)
    let payment_amount_del = ref(0)
    let show_ewt = ref(0)
    let less = ref(0)
    let subtotal = ref(0)
    let subtotal_disp = ref(0)
    let rfd_id = ref(0)
    let payment_id = ref(0)
    let vat_amount = ref(0)
    let cancel_all_reason = ref('')
    const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		getRFDPO()
        getSignatories()
        if(props.id!=0){
			poRFD()
		}
	})

    const poRFD = async () => {
       
        let response = await axios.get("/api/po_rfd_viewdetails/"+props.id);
        pr_head.value = response.data.pr_head;
        po_head.value = response.data.po_head;
        po_details.value = response.data.po_details;
        vendor.value = response.data.vendor;
        var total=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = val.total_cost;
			total += parseFloat(p);
        });
        var percent= po_head.value.vat_percent/100;
        // var percent= (vendor.value.vat==1) ? po_head.value.vat_percent/100 : 0;
        var new_vat= ((parseFloat(total) + parseFloat(po_head.value.shipping_cost) + parseFloat(po_head.value.handling_fee)) - parseFloat(po_head.value.discount)) * percent;
        vat_amount.value=parseFloat(new_vat)
        rfd_no.value = response.data.rfd_no;
        if(response.data.rfd_head!=null){
            rfd_head.value = response.data.rfd_head;
            if(rfd_head.value.status=='Draft'){
                rfd_id.value = response.data.rfd_head.id;
                rfd_no.value = (response.data.rfd_head.apv_no!=undefined) ? response.data.rfd_head.apv_no : response.data.rfd_no;
                company.value = response.data.rfd_head.company;
                pay_to.value = response.data.rfd_head.pay_to;
                rfd_date.value = response.data.rfd_head.rfd_date;
                check_name.value = response.data.rfd_head.check_name;
                due_date.value = response.data.rfd_head.due_date;
                payment_option.value = response.data.rfd_head.mode ?? 0;
                bank_no.value = response.data.rfd_head.bank_no;
                check_due.value = response.data.rfd_head.check_due;
                notes.value = response.data.rfd_head.notes;
                checked_by.value = response.data.rfd_head.checked_by;
                noted_by.value = response.data.rfd_head.noted_by;
                endorsed_by.value = response.data.rfd_head.endorsed_by;
                approved_by.value = response.data.rfd_head.approved_by;
                received_by.value = response.data.rfd_head.received_by;
            }
            show_ewt.value = response.data.rfd_head.show_ewt ?? 0;
            payment_list.value = response.data.rfd_payments;
        }
        prepared_by.value = response.data.prepared_by;
        total_per_item.value = total;
        // total_per_item.value = response.data.grand_total;
        var percent=vendor.value.ewt/100
        var payment_total = 0;
        payment_list.value.forEach(function (val, index, theArray) {
            payment_total += Number(val.payment_amount) || 0;
        });
        if(payment_list.value.length!=0){
           
            subtotal_disp.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + payment_total + parseFloat(payment_amount.value))
            subtotal.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + payment_total + parseFloat(payment_amount.value))
        }else{
            subtotal_disp.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + parseFloat(payment_amount.value))
            subtotal.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + parseFloat(payment_amount.value))
        }
        // subtotal.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee) - po_head.value.discount
        if(show_ewt.value!=0){
            if(vendor.value.vat==1){
                less.value = (subtotal.value/1.12)*percent 
                grand_total.value = subtotal.value + payment_total
                // grand_total.value = subtotal.value-less.value
                // subtotal.value = grand_total.value - (less.value + payment_total + parseFloat(payment_amount.value))
                subtotal.value = subtotal.value - (less.value + parseFloat(payment_amount.value))
            }else{
                less.value = subtotal.value*percent 
                grand_total.value = subtotal.value + payment_total
                // grand_total.value = subtotal.value-less.value
                // subtotal.value = grand_total.value - (less.value + payment_total + parseFloat(payment_amount.value))
                subtotal.value = subtotal.value - (less.value + parseFloat(payment_amount.value))
            }
        }else{
            grand_total.value = subtotal.value + payment_total
        }
	}

    const poRFDDisplay = async () => {
        if(props.id!=0){
            var po_head_id=props.id
        }else{
            var po_head_id=po_id.value
        }
		let response = await axios.get("/api/po_rfd_viewdetails/"+po_head_id);
        payment_list.value=response.data.rfd_payments;
	}

    const isNumber = (evt)=> {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode == 46) {
			//Check if the text already contains the . character
			if (evt.target.value.indexOf('.') === -1) {
				return true;
			} else {
				evt.preventDefault();
			}
		} else {
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				evt.preventDefault();
		}
		return true;
    }

    const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
	}

    const getRFDPO = async () => {
		let response = await axios.get("/api/get_rfd_po_dropdown");
		po_list.value = response.data.rfd_po_dropdown;
        // formatter.value = new Intl.NumberFormat('en-US', {
        //     minimumFractionDigits: 4,      
        // }) 
	}
    const generateRFDPO = async () => {
        if(po_id.value!=0){
            let response = await axios.get("/api/generate_rfd_po/"+po_id.value);
            rfd_no.value = response.data.rfd_no;
            pr_head.value = response.data.pr_head;
            po_head.value = response.data.po_head;
            po_details.value = response.data.po_details;
            vendor.value = response.data.vendor;
            total_per_item.value = response.data.grand_total;
            prepared_by.value = response.data.prepared_by;
            // payment_amount.value =response.data.total_payments;
            // payment_list.value =response.data.rfd_payments;
            rfd_no.value = response.data.rfd_no;
            if(response.data.rfd_head!=null){
                rfd_head.value = response.data.rfd_head;
                show_ewt.value = response.data.rfd_head.show_ewt ?? 0;
                payment_list.value = response.data.rfd_payments;
            }
            var percent=vendor.value.ewt/100
            var payment_total = 0;
            payment_list.value.forEach(function (val, index, theArray) {
                payment_total += Number(val.payment_amount) || 0;
            });
            var percent_vat= po_head.value.vat_percent/100;
            // var percent_vat= (vendor.value.vat==1) ? po_head.value.vat_percent/100 : 0;
            var new_vat= ((parseFloat(total_per_item.value) + parseFloat(po_head.value.shipping_cost) + parseFloat(po_head.value.handling_fee)) - parseFloat(po_head.value.discount)) * percent_vat;
            vat_amount.value=parseFloat(new_vat)
            if(payment_list.value.length!=0){
                subtotal_disp.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + payment_total + parseFloat(payment_amount.value))
                subtotal.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + payment_total + parseFloat(payment_amount.value))
            }else{
                subtotal_disp.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + parseFloat(payment_amount.value))
                subtotal.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + parseFloat(payment_amount.value))
            }
            // subtotal.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee) - po_head.value.discount
            if(show_ewt.value!=0){
                if(vendor.value.vat==1){
                    less.value = (subtotal.value/1.12)*percent 
                    grand_total.value = subtotal.value + payment_total
                    // grand_total.value = subtotal.value-less.value
                    subtotal.value = subtotal.value - (less.value + parseFloat(payment_amount.value))
                }else{
                    less.value = subtotal.value*percent 
                    // grand_total.value = subtotal.value-less.value
                    grand_total.value = subtotal.value + payment_total
                    subtotal.value = subtotal.value - (less.value + parseFloat(payment_amount.value))
                }
            }else{
                grand_total.value = subtotal.value + payment_total
                // grand_total.value = subtotal.value
            }
        }else{
            po_head.value=[]
        } 
	}
	const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}
    const openWarningAlert = () => {
		warningAlert.value = !warningAlert.value
	}
	const closeModal = () => {
		successAlert.value = !hideAlert.value
		// print_button.value = !print_button.value
		// save_button.value = hideAlert.value
        warningAlert.value = !hideAlert.value
	}

    const closeAlert = () => {
		dangerAlert.value = !hideAlert.value
		dangerAlerterrors.value = !hideAlert.value
        dangerAlert_payment.value = !hideAlert.value
        successAlertCD.value = !hideAlert.value
	}
    
    const showEwt = () => {
        var check=document.getElementById('ewtshow').checked;
        var payment_total = 0;
        payment_list.value.forEach(function (val, index, theArray) {
			payment_total += Number(val.payment_amount) || 0;
		});
        var percent=vendor.value.ewt/100
        if(payment_list.value.length!=0){
            subtotal.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + payment_total + parseFloat(payment_amount.value))
        }else{
            subtotal.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + vat_amount.value) - (po_head.value.discount + parseFloat(payment_amount.value))
        }
        if(check){
            if(vendor.value.vat==1){
                less.value = (subtotal.value/1.12)*percent 
                // grand_total.value = subtotal.value-less.value
                subtotal_disp.value = subtotal.value
                subtotal.value = subtotal.value-less.value
                // subtotal.value = grand_total.value - (parseFloat(payment_total) + parseFloat(payment_amount.value))
                // less.value = (subtotal.value/1.12)*percent 
            }else{
                less.value = subtotal.value*percent 
                // grand_total.value = subtotal.value-less.value
                subtotal_disp.value = subtotal.value
                subtotal.value = subtotal.value-less.value
                // subtotal.value = grand_total.value - (parseFloat(payment_total) + parseFloat(payment_amount.value))
                // less.value = (subtotal.value/1.12)*percent 
            }
        }else{
            // grand_total.value = (total_per_item.value + po_head.value.shipping_cost + po_head.value.handling_fee + po_head.value.vat_amount) - po_head.value.discount
            subtotal.value = subtotal.value
            less.value=0;
            // subtotal.value = grand_total.value - (parseFloat(payment_total) + parseFloat(payment_amount.value))
            // less.value = (subtotal.value/1.12)*percent
        }
    }
    
    // const checkPaymentAmount = () => {
    //     var payment_total = 0;
    //     var percent=vendor.value.ewt/100
    //     payment_list.value.forEach(function (val, index, theArray) {
	// 		payment_total +=  Number(val.payment_amount) || 0;
	// 	});
    //     if(payment_list.value.length!=0){
    //         subtotal.value = parseFloat(grand_total.value) - (parseFloat(payment_total) + parseFloat(payment_amount.value))
    //         less.value = (subtotal.value/1.12)*percent 
    //     }else{
    //         subtotal.value = parseFloat(grand_total.value) - parseFloat(payment_amount.value)
    //         less.value = (subtotal.value/1.12)*percent 
    //     }
    // }
    
    const addRowPayment= () => {
		if(payment_description.value!='' && payment_amount.value!=0){
			const payment = {
                id:0,
				payment_description:payment_description.value,
				payment_amount:payment_amount.value,
			}
			payment_list.value.push(payment)

            var payment_total = 0;
            var percent=vendor.value.ewt/100
			payment_description.value='';
			payment_amount.value=0;
            payment_list.value.forEach(function (val, index, theArray) {
                payment_total +=  Number(val.payment_amount) || 0;
            });
            
            subtotal_disp.value = parseFloat(grand_total.value) - parseFloat(payment_total)
            if(show_ewt.value!=0){
                less.value = (subtotal_disp.value/1.12)*percent 
            }else{
                less.value=0
            }
            // subtotal.value = parseFloat(grand_total.value) - parseFloat(payment_total) - less.value
            // if(show_ewt.value!=0){
            //     less.value = (subtotal_disp.value/1.12)*percent 
            // }else{
            //     less.value=0
            // }
            subtotal.value = parseFloat(grand_total.value) - parseFloat(payment_total) - less.value
			document.getElementById('check_description').placeholder="Payment Description"
			document.getElementById('check_description').style.backgroundColor = '#fef3c7';
            document.getElementById('check_amount').placeholder="Payment Amount"
			document.getElementById('check_amount').style.backgroundColor = '#fef3c7';
		}else{
			document.getElementById('check_description').placeholder="Please fill in payment description."
			document.getElementById('check_description').style.backgroundColor = '#FAA0A0';
            document.getElementById('check_amount').placeholder="Please fill in payment amount."
			document.getElementById('check_amount').style.backgroundColor = '#FAA0A0';
		}
	}
	const removePayment = (index,payment_amount) => {
        var check=document.getElementById('ewtshow').checked;
		payment_list.value.splice(index,1)
        var percent=vendor.value.ewt/100
        if(check){
            if(vendor.value.vat==1){
                var total=parseFloat(subtotal_disp.value) + parseFloat(payment_amount);
                less.value = (total/1.12)*percent 
                // grand_total.value = subtotal.value-less.value
                subtotal_disp.value = parseFloat(subtotal_disp.value) + parseFloat(payment_amount)
                subtotal.value = subtotal_disp.value - less.value
            }else{
                less.value =  (parseFloat(subtotal_disp.value) + parseFloat(payment_amount)) * percent 
                subtotal_disp.value = subtotal_disp.value + parseFloat(payment_amount)
                subtotal.value =  parseFloat(subtotal_disp.value) + parseFloat(payment_amount) - less.value
            }
        }else{
            subtotal_disp.value =  parseFloat(subtotal.value) + parseFloat(payment_amount)
            subtotal.value =  parseFloat(subtotal.value) + parseFloat(payment_amount)
        }
        // subtotal_disp.value = parseFloat(subtotal.value) + parseFloat(payment_amount)
        // less.value = (subtotal_disp.value/1.12)*percent 
        // subtotal.value = (parseFloat(subtotal_disp.value) + parseFloat(payment_amount)) - less.value
	}

    const onSave = (status) => {
		const formData= new FormData()
		// var total = document.querySelector("#grand_total").textContent;
		// var total_replace = total.replace(",", "");
        if(props.id!=0){
            var po_head_id=props.id
        }else{
            var po_head_id=po_id.value
        }
		formData.append('pr_no', po_head.value.pr_no)
		formData.append('po_no', po_head.value.po_no)
		formData.append('rfd_date', rfd_date.value)
		formData.append('apv_no', rfd_no.value)
		formData.append('due_date', due_date.value)
		formData.append('check_due', check_due.value)
		formData.append('check_name', check_name.value)
        formData.append('company', company.value)
        formData.append('bank_no', bank_no.value ?? '')
        formData.append('pay_to', pay_to.value)
        formData.append('mode', payment_option.value)
        formData.append('notes', notes.value ?? '')
		formData.append('grand_total', grand_total.value)
		formData.append('subtotal', subtotal_disp.value)
		formData.append('balance', subtotal.value)
		formData.append('status', status)
		formData.append('show_ewt', show_ewt.value)
		formData.append('ewt_amount', less.value)
		formData.append('checked_by', checked_by.value)
		formData.append('noted_by', noted_by.value)
		formData.append('endorsed_by', endorsed_by.value)
		formData.append('approved_by', approved_by.value)
		formData.append('received_by', received_by.value)
		formData.append('rfd_payments', JSON.stringify(rfd_payments.value))
		formData.append('payment_list', JSON.stringify(payment_list.value))
		formData.append('po_head_id', po_head_id)
		formData.append('rfd_id', rfd_id.value)
		if(status==='Saved'){
			if(checked_by.value!=0 && noted_by.value!=0 && endorsed_by.value!=0 && approved_by.value!=0 && received_by.value!=0 && company.value!='' && pay_to.value!='' && rfd_date.value!='' && payment_option.value!=0){
				axios.post(`/api/save_rfd_po`,formData).then(function (response) {
					rfd_id.value=response.data;
					success.value='You have successfully saved new rfd.'
					successAlert.value=!successAlert.value
				}, function (err) {
					error.value='Error! Please try again.';
					dangerAlerterrors.value=!dangerAlerterrors.value
				}); 
			}else{
				if(checked_by.value==0){
					document.getElementById('checked_by').style.backgroundColor = '#FAA0A0';
				}
                if(noted_by.value==0){
					document.getElementById('noted_by').style.backgroundColor = '#FAA0A0';
				}
                if(endorsed_by.value==0){
					document.getElementById('endorsed_by').style.backgroundColor = '#FAA0A0';
				}
				if(approved_by.value==0){
					document.getElementById('approved_by').style.backgroundColor = '#FAA0A0';
				}
				if(received_by.value==0){
					document.getElementById('received_by').style.backgroundColor = '#FAA0A0';
				}
                if(company.value==0){
					document.getElementById('company').style.backgroundColor = '#FAA0A0';
				}
                if(pay_to.value==0){
					document.getElementById('pay_to').style.backgroundColor = '#FAA0A0';
				}
                if(rfd_date.value==0){
					document.getElementById('rfd_date').style.backgroundColor = '#FAA0A0';
				}
                if(payment_option.value==0){
					document.getElementById('payment_option1').style.backgroundColor = '#FAA0A0';
					document.getElementById('payment_option2').style.backgroundColor = '#FAA0A0';
				}
				const btn_draft = document.getElementById("draft");
				btn_draft.disabled = true;
				const btn_save = document.getElementById("save");
				btn_save.disabled = true;
			}
		}else if(status==='Draft'){
			axios.post(`/api/save_rfd_po`,formData).then(function (response) {
				rfd_id.value=response.data;
				success.value='You have successfully draft new rfd.'
				warningAlert.value=!warningAlert.value
				// if(props.id!=0){
                //     payment_list.value=[]
				// 	poRFDDisplay()
				// }else{
                payment_list.value=[]
                poRFDDisplay()
				// }
				// successAlert.value=!successAlert.value
			}, function (err) {
				// error.value = err.response.data.message;
				error.value='Error! Please try again.';
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}
    }

    const resetError = (button) => {
		if(button==='button1'){
			document.getElementById('checked_by').style.backgroundColor = '#FEFCE8';
		}

        if(button==='button2'){
			document.getElementById('noted_by').style.backgroundColor = '#FEFCE8';
		}

		if(button==='button3'){
			document.getElementById('endorsed_by').style.backgroundColor = '#FEFCE8';
		}

        if(button==='button4'){
			document.getElementById('approved_by').style.backgroundColor = '#FEFCE8';
		}

        if(button==='button5'){
			document.getElementById('received_by').style.backgroundColor = '#FEFCE8';
		}

        if(button==='button6'){
			document.getElementById('company').style.backgroundColor = '#FEFCE8';
		}
        if(button==='button7'){
			document.getElementById('pay_to').style.backgroundColor = '#FEFCE8';
		}
        if(button==='button8'){
			document.getElementById('rfd_date').style.backgroundColor = '#FEFCE8';
		}
        if(button==='button9'){
			document.getElementById('payment_option1').style.backgroundColor = '#FEFCE8';
			document.getElementById('payment_option2').style.backgroundColor = '#FEFCE8';
		}
		const btn_draft = document.getElementById("draft");
		btn_draft.disabled = false;
		const btn_save = document.getElementById("save");
		btn_save.disabled = false;
	}

    const deletePayment = (id,option,payment_amount) => {
		if(option=='yes'){
			axios.get(`/api/delete_payment/`+id).then(function () {
				dangerAlert_payment.value = !hideAlert.value
				success.value='Successfully deleted payment!'
				successAlertCD.value = !successAlertCD.value
                var check=document.getElementById('ewtshow').checked;
                var percent=vendor.value.ewt/100
                if(check){
                    if(vendor.value.vat==1){
                        var total=parseFloat(subtotal_disp.value) + parseFloat(payment_amount);
                        less.value = (total/1.12)*percent 
                        // grand_total.value = subtotal.value-less.value
                        subtotal_disp.value = parseFloat(subtotal_disp.value) + parseFloat(payment_amount)
                        subtotal.value = subtotal_disp.value - less.value
                    }else{
                        less.value =  (parseFloat(subtotal_disp.value) + parseFloat(payment_amount)) * percent 
                        subtotal_disp.value = subtotal_disp.value + parseFloat(payment_amount)
                        subtotal.value =  parseFloat(subtotal_disp.value) + parseFloat(payment_amount) - less.value
                    }
                }else{
                    subtotal_disp.value =  parseFloat(subtotal.value) + parseFloat(payment_amount)
                    subtotal.value =  parseFloat(subtotal.value) + parseFloat(payment_amount)
                }
				poRFDDisplay()
				payment_list.value=[]
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			payment_id.value=id
			payment_amount_del.value=payment_amount
			dangerAlert_payment.value = !dangerAlert_payment.value
		}
	}

    const cancelAllRFD = (option) => {
		if(option=='yes'){
			if(cancel_all_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_all_reason', cancel_all_reason.value)
				axios.post(`/api/cancel_all_rfd/`+props.id,formData).then(function (response) {
                    dangerAlert.value = !hideAlert.value
                    success.value='Successfully cancelled RFD!'
                    successAlertCD.value = !successAlertCD.value
                    cancel_all_reason.value=''
                    document.getElementById('cancel_all_check').placeholder=""
                    document.getElementById('cancel_all_check').style.backgroundColor = '#FFFFFF';
                    window.location.reload()
				})
			}else{
				document.getElementById('cancel_all_check').placeholder="Cancel Reason must not be empty!"
				document.getElementById('cancel_all_check').style.backgroundColor = '#FAA0A0';
			}
		}else{
            cancel_all_reason.value=''
            document.getElementById('cancel_all_check').placeholder=""
            document.getElementById('cancel_all_check').style.backgroundColor = '#FFFFFF';
			dangerAlert.value = !dangerAlert.value
		}
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Request for Disbursement <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_disburse">Request for Disbursement</a></li>
                            <li class="breadcrumb-item active" aria-current="page">New</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
                    <div class="card-body">
                        <hr class="border-dashed mt-0">
                        <div class="pt-1">					
                            <div class="row">							
                                <div class="col-lg-6 offset-lg-3 col-md-3">
                                    <div class="form-group">
                                    <label class="text-gray-500 m-0" for="">Choose PO Number</label>
                                    <div class="input-group col-xs-12">
                                        <select class="form-control file-upload-info" v-model="po_id">
											<option value="0">--Select PO Number--</option>
											<option :value="p.id" v-for="p in po_list" :key="p.id">{{p.po_no}}{{ (p.revision_no!=0 && p.revision_no!=null) ? '.r'+p.revision_no : '' }}</option>
										</select>
                                        <span class="input-group-append">
                                            <button class="btn btn-primary" type="button" @click="generateRFDPO()">Select</button>
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-dashed">	
                            <div class="" v-if="po_head && po_head.length!=0">
                                
                                <table class="w-full text-sm table-borsdered">
                                    <tr>
                                        <td class="px-1 font-bold" width="10%">Company:</td>
                                        <td class="p-0" width="56%"><input type="text" class="w-full px-1 border-b" id="company" v-model="company" @click="resetError('button6')"></td>
                                        <td class="px-1 font-bold pl-4" width="12%">APV/RFD No.:</td>
                                        <td class="p-0" width="25%"><input type="text" class="w-full px-1 border-b" v-model="rfd_no" readonly></td>
                                    </tr>
                                    <tr>
                                        <td class="px-1 font-bold">Pay To:</td>
                                        <td class="p-0"><input type="text" class="w-full px-1 border-b" id="pay_to" v-model="pay_to" @click="resetError('button7')"></td>
                                        <td class="px-1 font-bold pl-4">Date:</td>
                                        <td class="p-0"><input type="date" class="w-full px-1 border-b" id="rfd_date" v-model="rfd_date" @click="resetError('button8')"></td>
                                    </tr>
                                    <tr>
                                        <td class="px-1 font-bold">Check Name:</td>
                                        <td class="p-0"><input type="text" class="w-full px-1 border-b" v-model="check_name"></td>
                                        <td class="px-1 font-bold pl-4">Due Date:</td>
                                        <td class="p-0"><input type="date" class="w-full px-1 border-b" v-model="due_date"></td>
                                    </tr>
                                    <tr>
                                        <td class="p-1" colspan="2">
                                            <div class="flex justify-start space-x-10">
                                                <div class="flex justify-between space-x-2 ml-5">
                                                    <input type="radio" name="cc" v-model="payment_option" value="1"  @click="resetError('button9')">
                                                    <span class="font-bold" id="payment_option1">Check</span>
                                                    <input type="radio" name="cc" v-model="payment_option" value="2" @click="resetError('button9')">
                                                    <span class="font-bold" id="payment_option2">Cash</span>
                                                </div>
                                                <div class="flex justify-between space-x-1 w-full">
                                                    <span class="font-bold w-20">Bank No.:</span>
                                                    <input type="text" class="w-full px-1 border-b" v-model="bank_no">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-1 font-bold pl-4">Check Due:</td>
                                        <td class="p-0"><input type="date" class="w-full px-1 border-b" v-model="check_due"></td>
                                    </tr>
                                </table>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="border-2">
                                            <table class="table-bordered w-full !text-xs">
                                                <tr class="bg-gray-100">
                                                    <td class="uppercase p-1 text-center" colspan="6">Explanation</td>
                                                    <td class="uppercase p-1 text-center" width="12%">Total</td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-y-none p-1 text-left" colspan="6">
                                                        <span>Payment for:</span>
                                                    </td>
                                                    <td class="border-y-none p-1 text-right"></td>
                                                </tr>
                                                <span hidden>{{ cancelled_qty=0 }}</span>
                                                <tr class="" v-for="(pd,index) in po_details">
                                                    <span hidden>{{ cancelled_qty+=(pd.status=='Cancelled') ? pd.total_cost : 0 }}</span>
                                                    <td class="border-y-none p-1 text-left" colspan="6">
                                                        {{ pd.quantity }} {{pd.uom}} {{pd.item_description}}, @ {{formatter.format(pd.unit_price)}} per {{pd.uom}}
                                                    </td>
                                                    <td class="border-y-none p-1 text-center">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span><input type="text" class="text-center tprice" :id="'tprice'+index" :value="formatter.format(pd.unit_price * pd.quantity)" readonly></span>
                                                            <!-- <span>{{ formatter.format(pd.unit_price * pd.quantity)}}</span> -->
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-y-none" colspan="6"><br></td>
                                                    <td class="border-y-none"></td>
                                                </tr>
                                                <tr class="">
                                                    <td class="" colspan="6"></td>
                                                    <td class=""></td>
                                                </tr>
                                                <tr class="">
                                                    <span hidden>{{ rowspan=9+payment_list.length }}</span>
                                                    <td class="border-r-none align-top p-2" colspan="4" width="65%" :rowspan="rowspan">
                                                        <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>{{pr_head.requestor}}</p>
                                                        <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>{{pr_head.enduse}}</p>
                                                        <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>{{pr_head.purpose}}</p>
                                                        <br>
                                                        <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PO Number:</span>{{po_head.po_no}}{{ (po_head.revision_no!=0 && po_head.revision_no!=null) ? '.r'+po_head.revision_no : '' }}</p>
                                                    </td>
                                                    <td class="border-l-none border-y-none p-0 text-right" colspan="2">
                                                        <div class="flex justify-between space-x-1">
                                                            <button type="button" class="btn btn-xs btn-primary p-0 px-1" @click="addRowPayment">
                                                                <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
                                                            </button>
                                                            <input type="text" class="w-full text-left bg-yellow-100 p-1" id="check_description" v-model="payment_description" placeholder="Payment Description">
                                                        </div>
                                                    </td>
                                                    <td class="p-0 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <button class="btn btn-xs p-0 !bg-yellow-100 ">
                                                            </button>
                                                            <input type="text" min="0" @keypress="isNumber($event)" class="w-full text-right bg-yellow-100 p-1" id="check_amount" v-model="payment_amount" placeholder="Payment Amount">
                                                        </div> 
                                                    </td> 
                                                </tr>
                                                <!-- <tr v-for="(rp,index) in rfd_payments">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">{{rp.payment_description}}</td>
													<td class="p-0 align-top" width="1">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span><input type="text" min="0" @keypress="isNumber($event)" class="w-full text-right p-1" id="check_amount" v-model="rp.payment_amount" placeholder="Payment Amount" readonly></span>
                                                            <button type="button" class="btn btn-danger p-1">
                                                                <XMarkIcon fill="none" @click="deletePayment(rp.id,'no')" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                                            </button>
                                                        </div>
													</td>
												</tr> -->
                                                <tr v-for="(pl,index) in payment_list">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">{{pl.payment_description}}</td>
													<td class="p-0 align-top" width="1">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span><input type="text" min="0" @keypress="isNumber($event)" class="w-full text-right p-1" id="check_amount" v-model="pl.payment_amount" placeholder="Payment Amount" readonly></span>
                                                            <button type="button" class="btn btn-danger p-1" v-if="pl.id==0">
                                                                <XMarkIcon fill="none" @click="removePayment(index,pl.payment_amount)" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                                            </button>
                                                            <button type="button" class="btn btn-danger p-1" v-else-if="pl.id!=0 && pl.po_rfd.status!='Saved'">
                                                                <XMarkIcon fill="none" @click="deletePayment(pl.id,'no',pl.payment_amount)" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                                            </button>
                                                        </div>
													</td>
												</tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">Shipping Cost</td>
                                                    <td class="p-1 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(po_head.shipping_cost)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
                                                    <td class="p-1 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(po_head.handling_fee)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                        
                                                        Less: Discount
                                                    </td>
                                                    <td class="p-1 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(po_head.discount)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">{{po_head.vat_percent}}% VAT</td>
                                                    <td class="p-1 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(vat_amount)}}</span>
                                                            <!-- <span>{{formatter.format(po_head.vat_amount)}}</span> -->
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">Sub Total</td>
                                                    <td class="p-1 border-t">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{ formatter.format(subtotal_disp)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                        <div class="flex justify-end space-x-3">
                                                            <span class="flex space-x-1">
                                                                <span class="pb-.5">Show EWT</span>
                                                                <input type="checkbox" id="ewtshow" alt="show" v-model="show_ewt" @click="showEwt()" true-value="1" false-value="0">
                                                            </span>
                                                            <span v-if="show_ewt!=0">Less: {{vendor.ewt}}% EWT</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-1 border-y-none" v-if="show_ewt!=0">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{ formatter.format(less)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">{{ (vendor.vat==1) ? 'Vatable' : 'Non-Vatable'}}</td>
                                                    <!-- <td class="p-1 border-y-none"></td> -->
                                                </tr>
                                                <!-- <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="2">Total Amount Due</td>
                                                    <td class="p-1 text-right font-bold !text-sm">{{ formatter.format(grand_total)}}</td>
                                                </tr> -->
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="2">Total Amount Due</td>
                                                    <td class="p-1 text-right font-bold !text-sm">{{ formatter.format(subtotal)}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="flex justify-start space-x-1">
                                                            <span class="p-1">Notes:</span>
                                                            <textarea class="w-full bg-yellow-50 p-1" placeholder="Add notes" v-model="notes"></textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <table class="w-full text-xs">
                                    <tr>
                                        <td class="" width="30%">Prepared by</td>
                                        <td width="5%"></td>
                                        <td class="" width="30%">Checked by</td>
                                        <td width="5%"></td>
                                        <td class="" width="30%">Noted by</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center border-b"><br></td>
                                        <td></td>
                                        <td class="text-center border-b"></td>
                                        <td></td>
                                        <td class="text-center border-b"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center p-1">{{prepared_by}}</td>
                                        <td></td>
                                        <td class="text-center p-1">
                                            <select class="text-center w-full bg-yellow-50 py-1" v-model="checked_by" id="checked_by" @click="resetError('button1')">
                                                <option value='0'>--Select Checked by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td class="text-center p-1">
                                            <select class="text-center w-full bg-yellow-50 py-1" v-model="noted_by" id="noted_by" @click="resetError('button2')">
                                                <option value='0'>--Select Noted by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><br><br></td>
                                        <td></td>
                                        <td class="text-center"></td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="" width="30%">Endorsed by</td>
                                        <td width="5%"></td>
                                        <td class="" width="30%">Approved by</td>
                                        <td width="5%"></td>
                                        <td class="" width="30%">Payment Received by</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center border-b"><br></td>
                                        <td></td>
                                        <td class="text-center border-b"></td>
                                        <td></td>
                                        <td class="text-center border-b"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center p-1">
                                            <select class="text-center w-full bg-yellow-50 p-1" v-model="endorsed_by" id="endorsed_by" @click="resetError('button3')">
                                                <option value='0'>--Select Endorsed by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td class="text-center p-1">
                                            <select class="text-center w-full bg-yellow-50 p-1" v-model="approved_by" id="approved_by" @click="resetError('button4')">
                                                <option value='0'>--Select Approved by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td class="text-center p-1">
                                            <select class="text-center w-full bg-yellow-50 p-1" v-model="received_by" id="received_by" @click="resetError('button5')">
                                                <option value='0'>--Select Payment Received by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <hr	class="border-dashed">
                                <div class="block" :class="{ hidden:save_button }">
                                    <div class="row my-2"> 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2" v-if="rfd_head.balance!=0">
                                                <button type="button" class="btn btn-danger w-36"  @click="cancelAllRFD('no')">Cancel RFD</button>
                                                <button type="button" class="btn btn-warning text-white w-36" @click="onSave('Draft')" id="draft">Save as Draft</button>
                                                <button type="button" class="btn btn-primary w-36" @click="onSave('Saved')" id="save">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class=" !hidden" :class="{ show:print_button }">
                                    <div class="row my-2"> 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2">
                                                <button type="submit" class="btn btn-primary mr-2 w-36">Print</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            
                        </div>
                    </div>
				</div>
			</div>
		</div>
        <Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlertCD }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-green-500 border-8 bg-green-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></CheckIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-green-400">Success!</h2>
									<h5 class="leading-tight">{{ success }}</h5>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
        <Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-green-500 border-8 bg-green-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></CheckIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-green-400">Success!</h2>
									<h5 class="leading-tight">You have successfully created a RFD.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a :href="'/pur_po/view/'+props.id" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-if="props.id!=0">Back to PO</a>
									<a :href="'/pur_po/view/'+po_id" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-else>Back to PO</a>
									<a :href="'/pur_disburse/view/'+rfd_id"  class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close & Print</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
        <Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:warningAlert }">
				<div @click="closeModal()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-yellow-400 border-8 bg-yellow-400 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></CheckIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-yellow-400">Success!</h2>
									<h5 class="leading-tight">You have successfully saved a RFD as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeModal()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/pur_disburse/new/0" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
        <Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlerterrors }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Error!</h2>
									<h5 class="leading-tight" v-if="error!=''" >{{ error }}</h5>
									<!-- <h5 class="leading-tight" v-else-if="error_inventory!=''">{{ error_inventory }}</h5>
									<h5 class="leading-tight" v-else-if="error_pr!=''" v-for="er in error_pr">{{ er }}</h5> -->
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeAlert()">Close</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
        <Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_payment }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this payment?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="deletePayment(payment_id,'yes',payment_amount_del)" >Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
        <Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">
										Are you sure you want to cancel this PO?<br>
										If yes, please state your reason.
									</h5>
									<label>Cancel Reason: </label>
									<textarea name="" id="cancel_all_check" class="form-control !border" rows="3" v-model="cancel_all_reason"></textarea>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelAllRFD('yes')">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>