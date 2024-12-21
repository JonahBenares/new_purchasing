<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref([]);
	const preview =  ref();
	const joi_list =  ref([]);
	const joi_head =  ref([]);
	const joi_labor =  ref([]);
	const joi_material =  ref([]);
	const jor_head=  ref([]);
	const rfd_head=  ref([]);
    const success =  ref()
	const error =  ref()
    const joi_det = ref(false)
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
    const formatter = new Intl.NumberFormat('en-US', { 
        minimumFractionDigits: 4 
    });
    let payment_list=ref([]);
    let p_description=ref("");
    let p_details=ref("");
    let p_amount=ref(0);
    let rows=ref(5);
    let joi_id=ref(0)
    let rfd_id=ref(0)
    let vat=ref(0)
    let vat_percent=ref(0)
    let vat_amount=ref(0)
    let retention_percent=ref(0)
    let retention_amount=ref(0)
    let total_per_item=ref(0);
    let less_labor=ref(0);
    let less_material=ref(0);
    let overall_total=ref(0);
    let grand_total=ref(0);
    let subtotal=ref(0)
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
    let signatories=ref([]);
    let payment_id = ref(0)
    let payment_amount_del = ref(0)
    let vat_amount_del = ref(0)
    let retention_amount_del = ref(0)
    let cancel_all_reason = ref('')
    const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
    onMounted(async () => {
		getRFDJOI()
        getSignatories()
        if(props.id!=0){
			joiRFD()
		}
	})
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

    const joiRFD = async () => {
        let response = await axios.get("/api/joi_rfd_viewdetails/"+props.id);
        rfd_no.value = response.data.rfd_no;
        joi_head.value = response.data.joi_head;
        jor_head.value = response.data.jor_head;
        joi_labor.value = response.data.joi_labor;
        joi_material.value = response.data.joi_material;
        vendor.value = response.data.vendor;
        total_per_item.value = parseFloat(response.data.total_sum_labor) + parseFloat(response.data.total_sum_material);
        prepared_by.value = response.data.prepared_by;
        rfd_no.value = response.data.rfd_no;
        if(response.data.rfd_head!=null){
            rfd_head.value = response.data.rfd_head;
            grand_total.value = response.data.rfd_head.grand_total;
            if(rfd_head.value.status=='Draft'){
                rfd_id.value = response.data.rfd_head.id;
                rfd_no.value = (response.data.rfd_head.rfd_no!=undefined) ? response.data.rfd_head.rfd_no : response.data.rfd_no;
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
            payment_list.value = response.data.rfd_payments;
        }
        var percent=vendor.value.ewt/100
        var percent_material=1/100
        var payment_total = 0;
        payment_list.value.forEach(function (val, index, theArray) {
            payment_total += Number(val.payment_amount) || 0;
        });

        var total=0;
        var totalm=0;
        joi_labor.value.forEach(function (val, index, theArray) {
            total += parseFloat(val.total_cost);
        });
        joi_material.value.forEach(function (val, index, theArray) {
            totalm += parseFloat(val.total_cost);
        });
            
        overall_total.value=(parseFloat(total) + parseFloat(totalm) + parseFloat(joi_head.value.vat_amount)) - (parseFloat(joi_head.value.discount) + parseFloat(joi_head.value.discount_material))
        if(vendor.value.vat==1){
            less_labor.value = (parseFloat(total)/1.12)*percent
            less_material.value = (parseFloat(totalm)/1.12)*percent_material
        }else{
            less_labor.value = parseFloat(total)*percent
            less_material.value = parseFloat(totalm)*percent_material
        }
        subtotal.value=parseFloat(overall_total.value) - parseFloat(payment_total)
	}

    const joiRFDDisplay = async () => {
        if(props.id!=0){
            var joi_head_id=props.id
        }else{
            var joi_head_id=joi_id.value
        }
		let response = await axios.get("/api/joi_rfd_viewdetails/"+joi_head_id);
        payment_list.value=response.data.rfd_payments;
	}

    const getRFDJOI = async () => {
		let response = await axios.get("/api/get_rfd_joi_dropdown");
		joi_list.value = response.data.rfd_joi_dropdown;
	}
    
    const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
	}
    
    const generateRFDJOI = async () => {
        if(joi_id.value!=0){
            let response = await axios.get("/api/generate_rfd_joi/"+joi_id.value);
            rfd_no.value = response.data.rfd_no;
            joi_head.value = response.data.joi_head;
            jor_head.value = response.data.jor_head;
            joi_labor.value = response.data.joi_labor;
            joi_material.value = response.data.joi_material;
            vendor.value = response.data.vendor;
            total_per_item.value = parseFloat(response.data.total_sum_labor) + parseFloat(response.data.total_sum_material);
            prepared_by.value = response.data.prepared_by;
            rfd_no.value = response.data.rfd_no;
            if(response.data.rfd_head!=null){
                rfd_head.value = response.data.rfd_head;
                payment_list.value = response.data.rfd_payments;
            }
            var percent=vendor.value.ewt/100
            var percent_material=1/100
            var payment_total = 0;
            payment_list.value.forEach(function (val, index, theArray) {
                payment_total += Number(val.payment_amount) || 0;
            });

            var total=0;
            var totalm=0;
            joi_labor.value.forEach(function (val, index, theArray) {
                total += parseFloat(val.total_cost);
            });
            joi_material.value.forEach(function (val, index, theArray) {
                totalm += parseFloat(val.total_cost);
            });
                
            overall_total.value=(parseFloat(total) + parseFloat(totalm) + parseFloat(joi_head.value.vat_amount)) - (parseFloat(joi_head.value.discount) + parseFloat(joi_head.value.discount_material))
            if(vendor.value.vat==1){
                less_labor.value = (parseFloat(total)/1.12)*percent
                less_material.value = (parseFloat(totalm)/1.12)*percent_material
            }else{
                less_labor.value = parseFloat(total)*percent
                less_material.value = parseFloat(totalm)*percent_material
            }
            subtotal.value=parseFloat(overall_total.value) - parseFloat(payment_total)
            // grand_total.value=parseFloat(overall_total.value) + parseFloat(payment_total)
        }else{
            joi_head.value=[]
        } 
	}

    const vatretChange = (vat_percent) => {
        var percent= (vat.value==1) ? vat_percent/100 : 0;
        if(vat.value==1){
            var new_vat = (parseFloat(p_amount.value)/1.12)  * parseFloat(percent);
        }else{
            var new_vat = (parseFloat(p_amount.value))  * parseFloat(percent);
        }
		vat_amount.value=new_vat.toFixed(4);
        if(retention_amount.value!=0){
            var retentionamnt=retention_amount.value
        }else{
            var retentionamnt=0
        }
        var new_total=parseFloat(p_amount.value) - (parseFloat(new_vat) + parseFloat(retentionamnt));
        grand_total.value=new_total.toFixed(4)
        // document.getElementById("grand_total").innerHTML=new_total.toFixed(4);

        var payment_total = 0;
        payment_list.value.forEach(function (val, index, theArray) {
            payment_total += Number(val.payment_amount) || 0;
        });
        var remaining_balance=parseFloat(subtotal.value) - (parseFloat(payment_total) + parseFloat(p_amount.value)) 
        document.getElementById("remaining_balance").innerHTML=remaining_balance.toFixed(4);
	}

    const changeSubtotal = () => {
        var payment_total = 0;
        payment_list.value.forEach(function (val, index, theArray) {
            payment_total += Number(val.payment_amount) || 0;
        });
        if(!isNaN(parseFloat(p_amount.value))){
            var subtotals=parseFloat(overall_total.value) - (parseFloat(p_amount.value) + parseFloat(payment_total))
            subtotal.value=subtotals.toFixed(4);
            // document.getElementById("subtotal").innerHTML=subtotals.toFixed(4);
        }else{
            subtotal.value=subtotal.value.toFixed(4);
            // document.getElementById("subtotal").innerHTML=subtotal.value.toFixed(4);
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
    
    const addPayment= () => {
		if(p_description.value ==''){
			document.getElementById('pdescription').placeholder="Please fill in Description."
			document.getElementById('pdescription').style.backgroundColor = '#FAA0A0';
		}else if(p_amount.value ==''){
            document.getElementById('pamount').placeholder="Please fill in Amount."
			document.getElementById('pamount').style.backgroundColor = '#FAA0A0';
        }else if(vat.value ==0){
            document.getElementById('pvat').placeholder="Please select value."
			document.getElementById('pvat').style.backgroundColor = '#FAA0A0';
        }else{
            changeSubtotal()
            const add_payments = {
                id:0,
				payment_description:p_description.value,
				payment_details:p_details.value,
				payment_amount:p_amount.value ?? 0,
				ewt_vat:vat.value,
				ewt_amount:vat_amount.value ?? 0,
				ewt_percent:vat_percent.value ?? 0,
				retention_percent:retention_percent.value ?? 0,
				retention_amount:retention_amount.value ?? 0
			}
			payment_list.value.push(add_payments)
            var payment_total = 0;
            var retentionamount = 0;
            payment_list.value.forEach(function (val, index, theArray) {
                var totaldue=Number(val.payment_amount) - Number(val.ewt_amount)
                payment_total += parseFloat(totaldue);
                retentionamount += Number(val.retention_amount) || 0;
            });
            var vatret_total = parseFloat(payment_total) - parseFloat(retentionamount)
            grand_total.value=vatret_total.toFixed(4)
            // document.getElementById("grand_total").innerHTML=vatret_total.toFixed(4);
			p_description.value='';
			p_details.value='';
			p_amount.value=0;
			vat.value=0;
			vat_amount.value=0;
			vat_percent.value=0;
			retention_percent.value=0;
			retention_amount.value=0;
			document.getElementById('pdescription').placeholder=""
			document.getElementById('pdescription').style.backgroundColor = '#FEF9C3';
            document.getElementById('pdescription').placeholder="Description"
            document.getElementById('pamount').placeholder=""
			document.getElementById('pamount').style.backgroundColor = '#FEF9C3';
            document.getElementById('pamount').placeholder="Amount"
            document.getElementById('pvat').placeholder=""
			document.getElementById('pvat').style.backgroundColor = '#FEF9C3';
            var row = rows + 1
            row++;
		}
	}
	const removePayment = (index,payment_amount,vat_amount,retention_amount) => {
        var vat_deduct = payment_amount - vat_amount
        var vatret_total = parseFloat(grand_total.value) - (vat_deduct - retention_amount)
        grand_total.value = vatret_total.toFixed(4)
        subtotal.value = parseFloat(subtotal.value ) + parseFloat(payment_amount)
		payment_list.value.splice(index,1)
	}

    const onSave = (status) => {
		const formData= new FormData()
        if(props.id!=0){
            var joi_head_id=props.id
        }else{
            var joi_head_id=joi_id.value
        }
		formData.append('jor_no', joi_head.value.jor_no)
		formData.append('joi_no', joi_head.value.joi_no)
		formData.append('rfd_date', rfd_date.value)
		formData.append('rfd_no', rfd_no.value)
		formData.append('due_date', due_date.value)
		formData.append('check_due', check_due.value)
		formData.append('check_name', check_name.value)
        formData.append('company', company.value)
        formData.append('bank_no', bank_no.value ?? '')
        formData.append('pay_to', pay_to.value)
        formData.append('mode', payment_option.value)
        formData.append('notes', notes.value ?? '')
		formData.append('grand_total', grand_total.value)
		formData.append('balance', subtotal.value)
		formData.append('status', status)
		formData.append('checked_by', checked_by.value)
		formData.append('noted_by', noted_by.value)
		formData.append('endorsed_by', endorsed_by.value)
		formData.append('approved_by', approved_by.value)
		formData.append('received_by', received_by.value)
		formData.append('payment_list', JSON.stringify(payment_list.value))
		formData.append('joi_head_id', joi_head_id)
		formData.append('rfd_id', rfd_id.value)
		if(status==='Saved'){
			if(checked_by.value!=0 && noted_by.value!=0 && endorsed_by.value!=0 && approved_by.value!=0 && received_by.value!=0){
				axios.post(`/api/save_joi_rfd`,formData).then(function (response) {
					rfd_id.value=response.data;
					success.value='You have successfully  new rfd.'
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
				const btn_draft = document.getElementById("draft");
				btn_draft.disabled = true;
				const btn_save = document.getElementById("save");
				btn_save.disabled = true;
			}
		}else if(status==='Draft'){
			axios.post(`/api/save_joi_rfd`,formData).then(function (response) {
				rfd_id.value=response.data;
				success.value='You have successfully draft new rfd.'
				warningAlert.value=!warningAlert.value
                payment_list.value=[]
                joiRFDDisplay()
			}, function (err) {
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
		const btn_draft = document.getElementById("draft");
		btn_draft.disabled = false;
		const btn_save = document.getElementById("save");
		btn_save.disabled = false;
	}

    const deleteJoiPayment = (id,option,payment_amount,vat_amount,retention_amount) => {
		if(option=='yes'){
			axios.get(`/api/delete_joi_payment/`+id).then(function () {
				dangerAlert_payment.value = !hideAlert.value
				success.value='Successfully deleted payment!'
				successAlertCD.value = !successAlertCD.value
                var vat_deduct = payment_amount - vat_amount
                var vatret_total = parseFloat(grand_total.value) - (vat_deduct - retention_amount)
                grand_total.value = vatret_total.toFixed(4)
                subtotal.value = parseFloat(subtotal.value ) + parseFloat(payment_amount)
				joiRFDDisplay()
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
			vat_amount_del.value=vat_amount
			retention_amount_del.value=retention_amount
			dangerAlert_payment.value = !dangerAlert_payment.value
		}
	}

    const cancelAllRFD = (option) => {
		if(option=='yes'){
			if(cancel_all_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_all_reason', cancel_all_reason.value)
				axios.post(`/api/cancel_all_joi_rfd/`+props.id,formData).then(function (response) {
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Disbursement <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_disburse">JO  Request for Disbursement</a></li>
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
                                    <label class="text-gray-500 m-0" for="">Choose JOI Number</label>
                                    <div class="input-group col-xs-12">
                                        <select class="form-control file-upload-info" v-model="joi_id">
                                            <option value="0">--Select JOI Number--</option>
											<option :value="j.id" v-for="j in joi_list" :key="j.id">{{j.joi_no}}{{ (j.revision_no!=0 && j.revision_no!=null) ? '.r'+j.revision_no : '' }}</option>
                                        </select>
                                        <span class="input-group-append">
                                            <button class="btn btn-primary" type="button" @click="generateRFDJOI()">Select</button>
                                            <!-- <button class="btn btn-primary" type="button" @click="joi_det = !joi_det">Select</button> -->
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-dashed">		
                            <div class="" v-if="joi_head && joi_head.length!=0">
                            <!-- <div class="" v-show="joi_det"> -->
                                <table class="w-full text-sm table-borsdered">
                                    <tr>
                                        <td class="px-1 font-bold" width="10%">Company:</td>
                                        <td class="p-0" width="56%"><input type="text" class="w-full px-1 border-b" v-model="company"></td>
                                        <td class="px-1 font-bold pl-4" width="12%">APV/RFD No.:</td>
                                        <td class="p-0" width="25%"><input type="text" class="w-full px-1 border-b" v-model="rfd_no"></td>
                                    </tr>
                                    <tr>
                                        <td class="px-1 font-bold">Pay To:</td>
                                        <td class="p-0"><input type="text" class="w-full px-1 border-b" v-model="pay_to"></td>
                                        <td class="px-1 font-bold pl-4">Date:</td>
                                        <td class="p-0"><input type="date" class="w-full px-1 border-b" v-model="rfd_date"></td>
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
                                                    <input type="radio" name="cc" v-model="payment_option" value="1">
                                                    <span class="font-bold">Check</span>
                                                    <input type="radio" name="cc" v-model="payment_option" value="2">
                                                    <span class="font-bold">Cash</span>
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
                                                    <td class="uppercase p-1 text-center" colspan="7">Explanation</td>
                                                    <td class="uppercase p-1 text-center" width="12%">Remarks</td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-y-none p-1 text-left" colspan="7">
                                                        <span>Payment for:</span>
                                                    </td>
                                                    <td class="border-y-none p-1 text-right"></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td  class="!border-none p-1" colspan="6">
                                                        <span class="font-bold">Supply of manpower/labor, laboratory tools/equipment, and
															technical expertise for the following:</span>
                                                    </td>
                                                    <td class="border-y-none p-1 text-center"></td>
                                                </tr> -->
                                                <tr class="">
                                                    <td class="!border-none p-1" colspan="4">
                                                        <span class="font-bold">{{jor_head.general_description}}</span>
                                                    </td>
                                                    <td class="!border-none p-1 text-center"></td>
                                                    <td class="!border-none p-1 text-center"></td>
                                                    <td class="!border-none p-1 text-right">Total Amount of JO</td>
                                                    <td class="border-y-none p-1 text-center">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span v-if="joi_head.grand_total==0">{{formatter.format(overall_total)}}</span>
                                                            <span v-else-if="joi_head.grand_total!=0">{{formatter.format(joi_head.grand_total)}}</span>
                                                            <span v-else>{{formatter.format(overall_total)}}</span>
                                                        </div>
                                                    </td>
												</tr>
                                                <tr class="" v-if="vendor.ewt!=0 && joi_head.grand_total==0"> 
                                                    <td clas s="!border-none p-1" colspan="4"></td>
                                                    <td class="!border-none p-1 text-center"></td>
                                                    <td class="!border-none p-1 text-center"></td>
                                                    <td class="!border-none p-1 text-right">{{vendor.ewt}}% Labor EWT</td>
                                                    <td class="border-y-none p-1 text-center">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(less_labor)}}</span>
                                                        </div>
                                                    </td>
												</tr>
                                                <tr class="" v-if="joi_head.grand_total==0">
                                                    <td class="!border-none p-1" colspan="4"></td>
                                                    <td class="!border-none p-1 text-center"></td>
                                                    <td class="!border-none p-1 text-center"></td>
                                                    <td class="!border-none p-1 text-right">1% Materials EWT</td>
                                                    <td class="border-y-none p-1 text-center">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(less_material)}}</span>
                                                        </div>
                                                    </td>
												</tr>
                                                <tr class="">
                                                    <td class="border-y-none p-1 text-left" colspan="6">
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>
                                                    </td>
                                                    <td class="border-y-none p-1 text-center">
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
                                                <template v-for="(p, indexes) in payment_list">
                                                    <tr class="">
                                                        <td colspan="4"></td>
                                                        <td class="border-l-none border-y-none p-0 text-right" colspan="2">
                                                            <div class="flex justify-between space-x-1">
                                                                <button type="button" @click="removePayment(indexes,p.payment_amount,p.ewt_amount,p.retention_amount)" class="btn btn-danger p-1" v-if="p.id==0">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                                                </button>
                                                                <button type="button" @click="deleteJoiPayment(p.id,'no',p.payment_amount,p.ewt_amount,p.retention_amount)" class="btn btn-danger p-1" v-else-if="p.id!=0 && p.joi_rfd.status!='Saved'">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                                                </button>
                                                                <input type="text" class="w-full text-left bg-yellow-100 p-1" v-model="p.payment_description"  readonly>
                                                            </div>
                                                        </td>
                                                        <td class="p-0 border-y-none">
                                                            <div class="flex justify-between w-full">
                                                                <button class="btn btn-xs p-0 !bg-yellow-100 ">
                                                                    <!-- <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></PlusIcon> -->
                                                                </button>
                                                                <input type="text" class="w-full text-left bg-yellow-100 p-1" v-model="p.payment_details" readonly>
                                                            </div>
                                                        </td>
                                                        <td class="p-0 border-y-none">
                                                            <div class="flex justify-between w-full">
                                                                <button class="btn btn-xs p-0 !bg-yellow-100 ">
                                                                    <!-- <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></PlusIcon> -->
                                                                </button>
                                                                <input type="text" class="w-full text-right bg-yellow-100 p-1" :value="formatter.format(p.payment_amount)" readonly>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="4"></td>
                                                        <td class="border-l-none border-y-none text-right p-0" colspan="3">
                                                            <div class="flex justify-end space-x-1">
                                                                <span class="p-1">EWT {{ (p.ewt_vat==1) ? 'VAT' : 'NON-VAT' }}</span>
                                                                <div class="flex " v-if="p.ewt_vat==1">
                                                                    <input type="text" class="w-10 text-center border p-1 bg-yellow-100" :value="p.ewt_percent" placeholder="%" readonly>%
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="p-0 border-y-none">
                                                            <div class="flex justify-between w-full">
                                                                <span class="p-1">₱</span>
                                                                <input type="text" class="w-20 text-right border p-1 bg-yellow-100" :value="formatter.format(p.ewt_amount)" placeholder="00" readonly>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="4"></td>
                                                        <td class="border-l-none border-y-none text-right p-0" colspan="3">
                                                            <div class="flex justify-end space-x-1">
                                                                <span class="p-1">Retention</span>
                                                                <!-- <div class="flex ">
                                                                    <input type="text" class="w-10 text-center border p-1 bg-yellow-100" :value="p.retention_percent" placeholder="%" readonly>%
                                                                </div> -->
                                                            </div>
                                                        </td>
                                                        <td class="p-0 border-y-none">
                                                            <div class="flex justify-between w-full">
                                                                <span class="p-1">₱</span>
                                                                <input type="text" class="w-20 text-right border p-1 bg-yellow-100" :value="formatter.format(p.retention_amount)" placeholder="00" readonly>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr class="">
                                                    <!--plus one sa rowspan if may additional nga description and amount-->
                                                    <td class="border-r-none align-top p-2" colspan="4" width="65%" :rowspan="rows"> 
                                                        <p class="m-0 mb-1 leading-none !text-xs"><span class="mr-2 uppercase">JOI Number:</span>{{jor_head.site_jor}}  / {{ joi_head.joi_no }}</p>
                                                        <!-- <p class="m-0 mb-1 leading-none !text-xs"><span class="mr-2 uppercase">DR Number:</span>DR-CENPRI-1001</p> -->
                                                    </td>
                                                    <td class="border-l-none border-y-none p-0 text-right" colspan="2">
                                                        <div class="flex justify-between space-x-1">
                                                            <button class="btn btn-xs btn-primary p-0 px-1" @click="addPayment">
                                                                <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
                                                            </button>
                                                            <!-- <button class="btn btn-xs btn-danger  p-0 px-1" @click="removePayment">
                                                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                                            </button> -->
                                                            <input type="text" class="w-full text-left bg-yellow-100 p-1" v-model="p_description" id="pdescription" placeholder="Description">
                                                        </div>
                                                    </td>
                                                    <td class="p-0 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <button class="btn btn-xs p-0 !bg-yellow-100 ">
                                                                <!-- <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></PlusIcon> -->
                                                            </button>
                                                            <input type="text" class="w-full text-left bg-yellow-100 p-1" v-model="p_details" id="pdetails" placeholder="Payment Details">
                                                        </div>
                                                    </td>
                                                    <td class="p-0 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <button class="btn btn-xs p-0 !bg-yellow-100 ">
                                                                <!-- <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></PlusIcon> -->
                                                            </button>
                                                            <input type="text" class="w-full text-right bg-yellow-100 p-1" v-model="p_amount" id="pamount" @keypress="isNumber($event)" placeholder="Amount">
                                                            <!-- <input type="text" class="w-full text-right bg-yellow-100 p-1" v-model="p_amount" id="pamount" @keypress="isNumber($event)" placeholder="Amount" @keyup="changeSubtotal()"> -->
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- <tr class="">
                                                    <td class="border-l-none border-y-none text-right p-1" colspan="3">Sub Total</td>
                                                    <td class="p-1 text-right !text-xs">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span id="subtotal">{{formatter.format(subtotal)}}</span>
                                                        </div>
                                                    </td>
                                                </tr> -->
                                                <tr class="">
                                                    <td class="border-l-none border-y-none text-right p-0" colspan="3">
                                                        <div class="flex justify-end space-x-1">
                                                            <!-- <span class="flex space-x-1">
                                                                <span class="pb-.5">Show</span>
                                                                <input type="checkbox" class="" alt="show">
                                                            </span> -->
                                                            <span class="p-1">EWT</span>
                                                            <div class="flex ">
                                                                <select name="" id="pvat" class="w-30 border p-1" v-model="vat">
                                                                    <option value="1">VAT</option>
                                                                    <option value="2">NON-VAT</option>
                                                                </select>
                                                                <input type="text" @keypress="isNumber($event)" class="w-10 text-center border p-1 bg-yellow-100" v-model="vat_percent" placeholder="%" @keyup="vatretChange(vat_percent)" @change="vatretChange(vat_percent)" v-if="vat==1">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="p-0 pr-0 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span class="p-1">₱</span>
                                                            <input type="text" class="w-20 text-right border p-1 bg-yellow-100" v-model="vat_amount" placeholder="00" @keypress="isNumber($event)">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none text-right p-0" colspan="3">
                                                        <div class="flex justify-end space-x-1">
                                                            <span class="p-1">Retention</span>
                                                            <!-- <div class="flex ">
                                                                <input type="text" class="w-10 text-center border p-1 bg-yellow-100" v-model="retention_percent" placeholder="%">
                                                            </div> -->
                                                        </div>
                                                    </td>
                                                    <td class="p-0 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span class="p-1">₱</span>
                                                            <input type="text" @keypress="isNumber($event)" class="w-20 text-right border p-1 bg-yellow-100" v-model="retention_amount" @keyup="vatretChange(vat_percent)" @change="vatretChange(vat_percent)" placeholder="00">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="3">Total Amount Due</td>
                                                    <td class="p-1 text-right font-bold !text-sm">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <!-- <span id="grand_total"></span> -->
                                                            <span id="grand_total">{{formatter.format(grand_total)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-0 text-right  " colspan="3">Remaining Balance</td>
                                                    <td class="px-1 text-right !text-xs">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span id="remaining_balance">{{formatter.format(subtotal)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8">
                                                        <div class="flex justify-start space-x-1">
                                                            <span class="p-1">Notes:</span>
                                                            <textarea class="w-full bg-yellow-100 p-1" placeholder="Add notes" v-model="notes"></textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br>
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
                                            <select class="text-center w-full bg-yellow-50" v-model="checked_by" id="checked_by" @click="resetError('button1')">
                                                <option value='0'>--Select Checked by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td class="text-center p-1">
                                            <select class="text-center w-full bg-yellow-50" v-model="noted_by" id="noted_by" @click="resetError('button2')">
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
                                            <select class="text-center w-full bg-yellow-50" v-model="endorsed_by" id="endorsed_by" @click="resetError('button3')">
                                                <option value='0'>--Select Endorsed by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td class="text-center p-1">
                                            <select class="text-center w-full bg-yellow-50" v-model="approved_by" id="approved_by" @click="resetError('button4')">
                                                <option value='0'>--Select Approved by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td class="text-center p-1">
                                            <select class="text-center w-full bg-yellow-50" v-model="received_by" id="received_by" @click="resetError('button5')">
                                                <option value='0'>--Select Payment Received by--</option>
                                                <option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <hr	class="border-dashed">
                                <div>
                                    <div class="row my-2"> 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2"  v-if="rfd_head.balance!=0">
                                                <button type="button" class="btn btn-danger w-36"  @click="cancelAllRFD('no')">Cancel RFD</button>
                                                <button type="button" class="btn btn-warning text-white mr-2 w-" @click="onSave('Draft')" id="draft">Save as Draft</button>
                                                <button type="button" class="btn btn-primary mr-2 w-36" @click="onSave('Saved')" id="save">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
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
									<a :href="'/job_issue/view/'+props.id" class="btn !bg-gray-100 btn-sm !rounded-full w-full"  v-if="props.id!=0">Back to JOI</a>
									<a :href="'/job_issue/view/'+joi_id" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-else>Back to JOI</a>
									<a :href="'/job_disburse/view/'+rfd_id" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</a>
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
									<h5 class="leading-tight">You have successfully  a RFD as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeModal()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/job_disburse/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
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
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="deleteJoiPayment(payment_id,'yes',payment_amount_del,vat_amount_del,retention_amount_del)" >Yes</button>
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