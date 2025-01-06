<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon, ExclamationTriangleIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/solid'
import moment from 'moment';
    import { reactive, ref , onMounted} from "vue"
    import { useRouter } from "vue-router"
	const router = useRouter();
	const vendor =  ref();
	const vendor_terms =  ref([]);
	const buttons_set =  ref()
	const approval_set =  ref(false)
	const error =  ref('');
	const success =  ref('');
	const dangerAlerterrors = ref(false)
	const dangerAlert_terms = ref(false)
	const dangerAlert_instructions = ref(false)
	const successAlertCD = ref(false)
	const dangerAlert = ref(false)
	const successAlert = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
    const approveAlert = ref(false)
	const hide_button = ref()
	const hideAlert = ref(true)
	const item_list =  ref([]);
	const po_head =  ref([]);
	const po_head_temp =  ref([]);
	const po_head_rev =  ref([]);
	const po_dr =  ref([]);
	const po_dr_rev =  ref([]);
	const po_dr_items =  ref([]);
	const po_vendor = ref([])
	const pr_head =  ref([]);
	const po_details =  ref([]);
	const po_details_temp =  ref([]);
	const po_terms = ref([])
	const po_terms_temp = ref([])
	const po_instructions = ref([])
	const po_instructions_temp = ref([])
	const rfq_terms =  ref([]);
	const orig_amount =  ref(0);
	const balance =  ref(0);
	const balance_overall =  ref(0);
	const remaining_balance =  ref([]);
	const prepared_by =  ref('');
	const checked_by_id =  ref(0);
	const checked_by =  ref('');
	const recommended_by =  ref('');
	const recommended_by_id =  ref(0);
	const approved_by =  ref('');
	const approved_by_id =  ref(0);
	const approved_by_rev =  ref(0);
	const approved_date =  ref('');
	const approved_reason =  ref('');
	let signatories=ref([]);
	const grand_total =  ref(0);
	const grand_total_old =  ref(0);
	const newvat =  ref(0);
	const totals =  ref(0);
	const vat =  ref(0);
	const vat_percent =  ref(12);
	const vat_amount =  ref(0);
	const vat_in_ex =  ref(0);
	const handling_fee =  ref(0);
	const shipping_cost =  ref(0);
	const discount =  ref(0);
	const new_data =  ref(0);
	const instruction_id =  ref(0);
	const terms_id =  ref(0);
	const itemno =  ref(0);
	const vendor_details_id =  ref(0);
	const item_description =  ref('');
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		poRevise()
		getSignatories()
	})
	const formatNumber = (number) => {
      return number.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
	const getRevisevalue= async () => {
		let response = await axios.get("/api/rpo_viewdetails/"+props.id);
	}
	const poRevise= async () => {
		let response = await axios.get("/api/rpo_viewdetails/"+props.id);
		po_head_rev.value = response.data.po_head_array;
		po_head.value = response.data.po_head;
		po_dr_rev.value = response.data.po_dr_array;
		po_dr.value = response.data.po_dr;
		po_dr_items.value = response.data.po_dr_items;
		shipping_cost.value = response.data.po_head.shipping_cost;
		handling_fee.value = response.data.po_head.handling_fee;
		discount.value = response.data.po_head.discount;
		vat.value = response.data.po_head.vat;
		vat_percent.value = (response.data.po_head.vat_percent!=0) ? response.data.po_head.vat_percent : 12;
		vat_amount.value = response.data.po_head.vat_amount;
		vat_in_ex.value = response.data.po_head.vat_in_ex;
		newvat.value= ((response.data.grand_total + response.data.po_head.shipping_cost + response.data.po_head.handling_fee) - response.data.po_head.discount) * (response.data.po_head.vat_percent/100)
		
		vendor_details_id.value = response.data.vendor_details_id;
		totals.value = response.data.grand_total;
		pr_head.value = response.data.pr_head;
		po_vendor.value = response.data.po_vendor;
		po_details.value = response.data.po_details_view;
		po_terms.value = response.data.po_terms;
		po_instructions.value = response.data.po_instructions;
		prepared_by.value = response.data.prepared_by;
		checked_by.value = response.data.checked_by;
		checked_by_id.value = response.data.checked_by_id;
		recommended_by.value = response.data.recommended_by;
		recommended_by_id.value = response.data.recommended_by_id;
		approved_by.value = response.data.approved_by;
		approved_by_id.value = response.data.approved_by_id;
		var cancelled_qty=0;
		response.data.po_details.forEach(function (val, index, theArray) {
			cancelled_qty +=(val.status=='Cancelled') ? val.total_cost : ''
			grand_total_old.value = (((response.data.grand_total-cancelled_qty) + response.data.po_head.shipping_cost + response.data.po_head.handling_fee - response.data.po_head.discount)) + newvat.value 
			grand_total.value = (((response.data.grand_total-cancelled_qty) + response.data.po_head.shipping_cost + response.data.po_head.handling_fee) - response.data.po_head.discount) + newvat.value
		});
		po_details.value.forEach(function (val, index, theArray) {
			checkRemainingQty(val.po_head_id,val.pr_details_id,index)
		});
		if(po_head.value.status=='Revised'){
			poReviseTemp()
			infoAlert.value = !hideAlert.value
			approval_set.value = !approval_set.value
			buttons_set.value = !hide_button.value
		}
	}

	const poReviseTemp= async () => {
		let response = await axios.get("/api/rpo_viewdetails/"+props.id);
		po_head.value = response.data.po_head;
		po_head_temp.value = response.data.po_head_temp;
		po_details_temp.value = response.data.po_details_temp;
		po_terms_temp.value = response.data.po_terms_temp;
		po_instructions_temp.value = response.data.po_instructions_temp;
		vendor_details_id.value = response.data.vendor_details_id;
	}
	const openDangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
    const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}
	const openInfoAlert = () => {
		infoAlert.value = !infoAlert.value
	}
	const openApproveAlert = () => {
		approveAlert.value = !approveAlert.value
	}
	const openWarningAlert = () => {
		warningAlert.value = !warningAlert.value
	}
	const closeAlert = () => {
		dangerAlerterrors.value=!hideAlert.value 
		dangerAlert_terms.value=!hideAlert.value 
		dangerAlert_instructions.value=!hideAlert.value 
		successAlertCD.value=!hideAlert.value 
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		warningAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
		approveAlert.value = !hideAlert.value
		showModal.value = !hideAlert.value
	}

	const closeInfoAlert = () => {
		infoAlert.value = !hideAlert.value
	}
	const closeInfoAlert2 = () => {
		infoAlert.value = !hideAlert.value
		approval_set.value = !approval_set.value
		buttons_set.value = !hide_button.value
	}

	const showAddVendor = ref(false)
	const showPreview = ref(false)
	const hideModal = ref(true)
	const showModal = ref(false)
	const openAddVendor = () => {
		showAddVendor.value = !showAddVendor.value
	}
	const openPreview = () => {
		showPreview.value = !showPreview.value
	}
	const closeModal = () => {
		showAddVendor.value = !hideModal.value
		showPreview.value = !hideModal.value
		showModal.value = !hideAlert.value
	}
	const save_button = ref();

	const openModel = (item_no) => {
		itemno.value = item_no
		showModal.value = !showModal.value
	}
	
	const getPOItems = async () => {
		let response = await axios.get("/api/get_po_items/"+item_description.value+'/'+vendor_details_id.value);
		document.getElementById("repeat_po_items").style.display="block"
		item_list.value = response.data.po_items;
	}

	const addRepeatItem = (index) => {
		let i = itemno.value
		let repeat_item_description = document.getElementById("ritem_description"+index);
		let reference_po_no = document.getElementById("rpono"+index).value
		let qty = document.getElementById("balance_checker"+i).value
		let unitprice = document.getElementById("runitprice"+index).value

		po_details.value[i].offer_desc = repeat_item_description.innerText
		po_details.value[i].unit_price = parseFloat(unitprice)
		po_details.value[i].reference_po_no = reference_po_no
		po_details.value[i].reference_po_details_id = document.getElementById("rpodetailsid"+index).value
		po_details.value[i].currency = document.getElementById("offercurrency"+index).value

		po_details.value[i].totalprice = parseFloat(qty) * parseFloat(unitprice);
		grand_total.value=parseFloat(grand_total.value) +  parseFloat(po_details.value[i].totalprice)

		showModal.value = !hideModal.value
		item_list.value = []
		item_description.value = ''

		// const reference = {
		// 	r_no:i+1,
		// 	ref_po_no:reference_po_no,
		// }
		// po_references.value.push(reference)
	}

	const removeOffer = (index) => {
		grand_total.value=grand_total.value - po_details.value[index].totalprice;
		po_details.value[index].offer_desc = ''
		po_details.value[index].unit_price = ''
		po_details.value[index].reference_po_no = ''
		po_details.value[index].reference_po_details_id = ''
		po_details.value[index].currency = ''
		po_details.value[index].totalprice = ''
		
	}

	let terms_list=ref([]);
	let terms_text=ref("");
	let other_list=ref([]);
	let other_text=ref("");
	const addRowTerms= () => {
		if(terms_text.value!=''){
			const terms = {
				terms_condition:terms_text.value,
			}
			terms_list.value.push(terms)
			terms_text.value='';
			document.getElementById('check_terms').placeholder=""
			document.getElementById('check_terms').style.backgroundColor = '#FEFCE8';
		}else{
			document.getElementById('check_terms').placeholder="Please fill in Terms and Condition."
			document.getElementById('check_terms').style.backgroundColor = '#FAA0A0';
		}
	}

	const removeTerms = (index) => {
		terms_list.value.splice(index,1)
	}

	const deleteTerms = (id,option) => {
		if(option=='yes'){
			axios.get(`/api/delete_rpo_terms/`+id).then(function () {
				dangerAlert_terms.value = !hideAlert.value
				success.value='Successfully deleted term!'
				successAlertCD.value = !successAlertCD.value
				rpoDraft()
				terms_list.value=[]
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
			rpoDraftDisplay()
		}else{
			terms_id.value=id
			dangerAlert_terms.value = !dangerAlert_terms.value
		}
	}

	const addRowOther= () => {
		if(other_text.value!=''){
			const others = {
				id:0,
				instructions:other_text.value,
			}
			other_list.value.push(others)
			other_text.value='';
			document.getElementById('check_others').placeholder=""
			document.getElementById('check_others').style.backgroundColor = '#FEFCE8';
		}else{
			document.getElementById('check_others').placeholder="Please fill in Other instructions."
			document.getElementById('check_others').style.backgroundColor = '#FAA0A0';
		}
	}

	const removeOthers = (index) => {
		other_list.value.splice(index,1)
	}

	const deleteInstructions = (id,option) => {
		if(option=='yes'){
			axios.get(`/api/delete_rpo_instructions/`+id).then(function () {
				dangerAlert_instructions.value = !hideAlert.value
				success.value='Successfully deleted instruction!'
				successAlertCD.value = !successAlertCD.value
				rpoDraft()
				other_list.value=[]
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
			rpoDraftDisplay()
		}else{
			instruction_id.value=id
			dangerAlert_instructions.value = !dangerAlert_instructions.value
		}
	}

	const printDiv = () => {
		window.print();
	}

	const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
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
	
	const additionalCost = (vat_percent) =>{
		// if(props.id==0){
		// 	var total = (orig_amount.value==0) ? grand_total.value : orig_amount.value;
		// }else{
		// 	var total = parseFloat(totals.value)
		// }
		var total=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			total += parseFloat(pi);
        });
		var discount_display= (discount.value!='') ? discount.value : 0;
		// var vat_percent = document.getElementById("vat_percent").value;
		var percent=vat_percent/100;
		var new_vat= ((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * percent;
		var new_total = ((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) + new_vat ;
		document.getElementById("grand_total").innerHTML  = new_total.toFixed(2)
		new_data.value=parseFloat(new_total)
		document.getElementById("vat_amount").value=new_vat.toFixed(2);
		vat_amount.value=new_vat.toFixed(2);
	}

	const vatChange = (vat_percent) => {
		// var total = (orig_amount.value==0) ? grand_total.value : orig_amount.value;
		var grandtotal=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			grandtotal += parseFloat(pi);
        });
		var discount_display= (discount.value!='') ? discount.value : 0;
		// var vat_percent = document.getElementById("vat_percent").value;
        var percent=vat_percent/100;
        var new_vat = ((parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * parseFloat(percent);
        document.getElementById("vat_amount").value = new_vat.toFixed(2);
		vat_amount.value=new_vat.toFixed(2);
        var new_total=((parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) + parseFloat(new_vat);
        document.getElementById("grand_total").innerHTML=new_total.toFixed(2);
		// new_data.value=parseFloat(new_total)
	} 

	const selectVat = (vat_percent) => {
		if(vat.value==1){
			var total=0;
			po_details.value.forEach(function (val, index, theArray) {
				var p = document.getElementById('tprice'+index).value;
				if(p != '' && p != NaN){
					var pi = p.replace(",", "");
					total += parseFloat(pi);
				}
			});
			var discount_display= (discount.value!='') ? discount.value : 0;
			var percent=vat_percent/100;
			vat_amount.value=((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * parseFloat(percent);
			ChangeGrandTotal(vat_percent)
		}else{
			vat_amount.value=0
			ChangeGrandTotal(vat_percent)
		}
	}

	const ChangeGrandTotal = (vat_percent) => {
		// formatter.value = new Intl.NumberFormat('en-US', {
		// 	minimumFractionDigits: 4,      
		// })
		var total=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			if(p != '' && p != NaN){
				var pi = p.replace(",", "");
				total += parseFloat(pi);
			}
			
        });
		var discount_display= (discount.value!='') ? discount.value : 0;
		var percent= (vat.value==1) ? vat_percent/100 : 0;
		var new_vat= ((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * percent;
		var new_total = ((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) + new_vat;
		grand_total.value = new_total;
		new_data.value=parseFloat(new_total)
		document.getElementById("vat_amount").value=new_vat.toFixed(2);
		vat_amount.value=new_vat.toFixed(2);
	}

	// const checkBalance = async (pr_details_id,qty,count) => {
	// 	var grandtotal=0;
	// 	po_details.value.forEach(function (val, index, theArray) {
	// 		var p = document.getElementById('tprice'+index).value;
	// 		grandtotal += parseFloat(p);
    //     });
	// 	var vat = document.getElementById("vat_percent").value;
    //     var percent=vat/100;
	// 	var new_vat = (parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) * parseFloat(percent);
	// 	vat_amount.value=new_vat;
		
	// 	var discount_display= (discount.value!='') ? discount.value : 0;
	// 	var overall_total = (parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value) + parseFloat(new_vat)) - parseFloat(discount_display);
	// 	grand_total.value=formatNumber(overall_total);

	// 	// grand_total.value=formatNumber(grandtotal + new_vat);
	// 	orig_amount.value=formatNumber(grandtotal);
	// 	let response = await axios.get("/api/check_balance/"+pr_details_id);
	// 	balance.value = response.data.balance;
	// 	var po_qty=balance.value.po_qty + balance.value.dpo_qty + balance.value.rpo_qty
	// 	var all_qty=balance.value.pr_qty - po_qty
	// 	if(qty>all_qty){
	// 		document.getElementById('balance_checker'+count).style.backgroundColor = '#FAA0A0';
	// 		const btn_draft = document.getElementById("draft");
	// 		btn_draft.disabled = true;
	// 		const btn_save = document.getElementById("save");
	// 		btn_save.disabled = true;
	// 	}else{
	// 		document.getElementById('balance_checker'+count).style.backgroundColor = '#FEFCE8';
	// 		const btn_draft = document.getElementById("draft");
	// 		btn_draft.disabled = false;
	// 		const btn_save = document.getElementById("save");
	// 		btn_save.disabled = false;
	// 	}
	// }

	const checkBalance = async (po_head_id,pr_details_id,qty,count,vat_percent) => {
		var grandtotal=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			grandtotal += parseFloat(pi);
        });

		// var vat = document.getElementById("vat_percent").value;
		var discount_display= (discount.value!='') ? discount.value : 0;
		var percent= (vat.value==1) ? vat_percent/100 : 0;
		var new_vat = ((parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * parseFloat(percent);
		vat_amount.value=new_vat;
		var overall_total = ((parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) + parseFloat(new_vat) ;
		grand_total.value=overall_total.toFixed(2);
		orig_amount.value=grandtotal.toFixed(2);
		let response = await axios.get("/api/check_balance_rev/"+po_head_id+'/'+pr_details_id);
		balance.value = response.data.balance;
		balance_overall.value = response.data.balance_overall;
		var po_qty=balance_overall.value.po_qty + balance_overall.value.dpo_qty + balance_overall.value.rpo_qty
		var all_qty=balance_overall.value.pr_qty - po_qty
		var total_qty = all_qty + po_qty;
		po_details.value[count].totalprice = qty * po_details.value[count].unit_price
	
		if(qty>total_qty){
			document.getElementById('balance_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}
	const checkRemainingQty = async (po_head_id,pr_details_id,count) => {
		let response = await axios.get("/api/check_balance_rev/"+po_head_id+'/'+pr_details_id);
		remaining_balance.value[count] = response.data.balance.quantity;
	}

	const onSave = () => {
		const formData= new FormData()
		var total = document.querySelector("#grand_total").textContent;
		var total_replace = total.replace(",", "");
		formData.append('po_head', JSON.stringify(po_head_rev.value))
		formData.append('shipping_cost', shipping_cost.value)
		formData.append('handling_fee', handling_fee.value)
		formData.append('discount', discount.value)
		formData.append('vat', vat.value)
		formData.append('vat_percent', (vat.value!=0) ? vat_percent.value : 0)
		formData.append('vat_amount', vat_amount.value)
		formData.append('vat_in_ex', vat_in_ex.value)
		formData.append('grand_total', total_replace)
		formData.append('po_dr', JSON.stringify(po_dr_rev.value))
		formData.append('po_dr_items', JSON.stringify(po_dr_items.value))
		formData.append('terms_list', JSON.stringify(terms_list.value))
		formData.append('po_terms', JSON.stringify(po_terms.value))
		formData.append('po_instructions', JSON.stringify(po_instructions.value))
		formData.append('other_list', JSON.stringify(other_list.value))
		formData.append('po_details', JSON.stringify(po_details.value))
		formData.append('internal_comment', po_head.value.internal_comment ?? '')
		formData.append('props_id', props.id)
		po_details.value.forEach(function (val, index, theArray) {
			formData.append('quantity'+index, remaining_balance.value[index])
		});
		const btn_save = document.getElementById("confirm_alert");
		btn_save.disabled = true;
		axios.post(`/api/save_change_rpo`,formData).then(function (response) {
			infoAlert.value = !hideAlert.value
			approval_set.value = !approval_set.value
			buttons_set.value = !hide_button.value
			success.value='You have successfully revise po, please fill in approve form below.'
			successAlertCD.value=!successAlertCD.value
			poReviseTemp()
			setTimeout(() => {
				closeAlert()
			}, 2000);
		}, function (err) {
			error.value='Error! Please try again.';
			dangerAlerterrors.value=!dangerAlerterrors.value
		}); 
    }

	const onSaveApprove = () => {
		const formData= new FormData()
		var total = document.querySelector("#grand_total").textContent;
		formData.append('po_head', JSON.stringify(po_head_rev.value))
		formData.append('po_dr', JSON.stringify(po_dr_rev.value))
		formData.append('po_dr_items', JSON.stringify(po_dr_items.value))
		formData.append('approved_by_rev', approved_by_rev.value)
		formData.append('approved_date', approved_date.value)
		formData.append('approved_reason', approved_reason.value)
		formData.append('shipping_cost', shipping_cost.value)
		formData.append('handling_fee', handling_fee.value)
		formData.append('discount', discount.value)
		formData.append('vat', vat.value)
		formData.append('vat_percent', (vat.value!=0) ? vat_percent.value : 0)
		formData.append('vat_amount', vat_amount.value)
		formData.append('vat_in_ex', vat_in_ex.value)
		formData.append('grand_total', total)
		formData.append('internal_comment', po_head.value.internal_comment ?? '')
		formData.append('terms_list', JSON.stringify(terms_list.value))
		formData.append('po_terms', JSON.stringify(po_terms.value))
		formData.append('po_instructions', JSON.stringify(po_instructions.value))
		formData.append('other_list', JSON.stringify(other_list.value))
		formData.append('po_details', JSON.stringify(po_details.value))
		formData.append('props_id', props.id)
		po_details.value.forEach(function (val, index, theArray) {
			formData.append('quantity'+index, remaining_balance.value[index])
		});
		if(approved_by_rev.value!=0 && approved_date.value!=''){
			const btn_save = document.getElementById("save_approve");
			btn_save.disabled = true;
			axios.post(`/api/save_approved_repeat_revision`,formData).then(function (response) {
				success.value='You have successfully revised po'
				successAlertCD.value=!successAlertCD.value
				setTimeout(() => {
					router.push('/po_repeat/view/'+props.id)
				}, 1000);
			}, function (err) {
				error.value='Error! Please try again.';
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}else{
			if(approved_by_rev.value==0){
				document.getElementById('approved_by_rev').style.backgroundColor = '#FAA0A0';
			}
			if(approved_date.value==0){
				document.getElementById('approved_date').style.backgroundColor = '#FAA0A0';
			}
			const btn_save = document.getElementById("save_approve");
			btn_save.disabled = true;
		}
    }

	const resetError = (button) => {
		if(button==='button1'){
			document.getElementById('approved_date').style.backgroundColor = '#FFFFFF';
		}

		if(button==='button2'){
			document.getElementById('approved_by_rev').style.backgroundColor = '#FFFFFF';
		}
		const btn_save = document.getElementById("save_approve");
		btn_save.disabled = false;
	}
</script>
<template>
	<navigation>
		<div class="row"  id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Purchase Order <small>Revise</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_po">Purchase Order</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Revise</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<div class="pt-1" id="printable">
						<span class="font-bold uppercase text-lg text-center text-yellow-500 print:hidden">CHANGE ORDER FORM</span>
						<hr class="block border-dashed print:hidden">
						<div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase leading_none">Purchase Order</span>
								</div>
								<div class="flex justify-center">
									<span class="uppercase text-xs leading_none text-yellow-500">Change Order Form</span>
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
						<div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 ">
									<span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
									<span class="text-sm text-gray-700">{{po_head.po_no}}</span>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">{{moment(po_head.po_date).format('MMMM DD,YYYY')}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 ">
									<span class="text-sm text-gray-700 font-bold pr-1">Supplier: </span>
									<span class="text-sm text-gray-700">{{po_vendor.vendor_name }} ({{ po_vendor.identifier }})</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 ">
									<span class="text-sm text-gray-700 font-bold pr-1">Address:</span>
									<span class="text-sm text-gray-700">{{po_vendor.address }}</span>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Telephone: </span>
									<span class="text-sm text-gray-700">{{po_vendor.phone }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 ">
									<span class="text-sm text-gray-700 font-bold pr-1">Contact Person: </span>
									<span class="text-sm text-gray-700">{{po_vendor.contact_person }}</span>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Telefax: </span>
									<span class="text-sm text-gray-700">{{po_vendor.fax }}</span>
								</div>
							</div>
							
							<div class="" >
								<br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="font-bold uppercase text-sm text-gray-500">Current Data</span>
                                        <div class="border-2">
											<table class="table-bordered w-full !text-xs">
												<tr class="bg-gray-100">
													<td class="uppercase p-1 text-center" width="3%">#</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1" colspan="2" width="25%">Description</td>
													<td class="uppercase p-1" colspan="2" width="25%">Offer</td>
													<td class="uppercase p-1 text-center" width="8%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="8%">Total</td>
												</tr>
												<tr class="" v-for="(pd,index) in po_details">
													<td class="border-y-none p-1 text-center">{{index+1}}</td>
													<td class="border-y-none p-1 text-center">{{ pd.quantity }}</td>
													<td class="border-y-none p-1 text-center">{{pd.uom}}</td>
													<td class="border-y-none p-1" colspan="2">{{pd.item_description}}</td>
													<td class="p-1"  colspan="2">
														<input type="text" class="p-1 bg-yellsow-100 v w-full" v-model="pd.reference_po_no" readonly>
														<input type="text" class="p-1 bg-yellsow-100 v w-full" v-model="pd.offer_desc" readonly>
													</td>
													<td class="border-y-none p-1 text-center">{{formatNumber(pd.unit_price)}} {{ pd.currency }}</td>
													<td class="border-y-none p-1 text-center">{{formatNumber(pd.unit_price * pd.quantity)}}</td>
												</tr>
												<tr class="">
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
												</tr>
												<tr class="">
													<td class="border-r-none align-top p-2" colspan="6" width="65%" rowspan="5">
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>{{po_head.pr_no}}</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>{{pr_head.requestor}}</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>{{pr_head.enduse}}</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>{{pr_head.purpose}}</p>
													</td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
													<td class="p-1 text-right ">{{formatNumber(po_head.shipping_cost ?? 0)}}</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
													<td class="p-1 text-right ">{{formatNumber(po_head.handling_fee ?? 0)}}</td>
												</tr>
												<tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
                                                        <td class="p-1 text-right ">{{formatNumber(po_head.discount ?? 0)}}</td>
                                                    </tr>
                                                    <tr class="" v-if="po_head.vat==1">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT</td>
                                                        <td class="p-0">
                                                            <div class="flex">
                                                                <input type="text" class="w-10 bg-white border-r text-center" disabled :value="po_head.vat_percent+'%'">
                                                                <input type="text" class="w-10 bg-white border-r text-center" disabled value="12" hidden>
                                                                <input type="text" class="w-full bg-white p-1 text-right" disabled :value="formatNumber(po_head.vat_amount ?? 0)">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="" v-else>
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">NON-VAT</td>
                                                        <td class="p-0">
                                                            <div class="flex">
                                                                <input type="text" class="w-full bg-white p-1 text-right" disabled value="0.00">
                                                            </div>
                                                        </td>
                                                    </tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">{{formatNumber(grand_total_old ?? 0)}}</td>
												</tr>
											</table>
										</div>
                                    </div>
                                </div>
                                <br>
								<div class="row">
									<div class="col-lg-12">
                                        <span class="font-bold uppercase text-sm text-yellow-500">New Data</span>
										<div class="border-2 border-yellow-400">
											<table class="table-bordered w-full !text-xs ">
                                                <!-- <tr>
                                                    <td colspan="7" class=" text-center  font-bold text-yellow-600 p-1 text-sm uppercase">New Data</td>
                                                </tr> -->
												<tr class="bg-yellow-100">
													<td class="uppercase p-1 text-center" width="3%">#</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1" colspan="2" width="25%">Description</td>
													<td class="uppercase p-1" colspan="2" width="25%">Offer</td>
													<td class="uppercase p-1 text-center" width="8%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="8%">Total</td>
													<td class="p-1 text-center" align="center" width="1%">
														<span>
															<MagnifyingGlassIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></MagnifyingGlassIcon>
														</span>
													</td>
												</tr>
												<tr class="" v-for="(pd, index) in po_details" v-if="po_head.status!='Revised'">
													<span hidden>{{ totalprice=formatNumber(pd.unit_price * remaining_balance[index]) }}</span>
													<td class="border-y-none p-1 text-center">{{ index+1}}</td>
													<td class="border-y-none p-0 text-center">
														<!-- <input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 border-b p-1 text-center" :id="'balance_checker'+index" v-model="remaining_balance[index]"> -->
														<input type="text" min="0" @keyup="checkBalance(pd.po_head_id,pd.pr_details_id,remaining_balance[index], index,vat_percent)" @change="checkBalance(pd.po_head_id,pd.pr_details_id,remaining_balance[index], index,vat_percent)" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 border-b p-1 text-center" :id="'balance_checker'+index" v-model="remaining_balance[index]">
													</td>
													<td class="border-y-none p-1 text-center">{{ pd.uom }}</td>
													<td class="border-y-none p-1" colspan="2">{{ pd.item_description }}</td>
													<td class="p-1" colspan="2">
														<div class="flex justify-between space-x-1 w-full" >
															<div class="w-full">
																<!-- <p class="w-full text-xs m-0 font-bold">{{ pd.offer_supp }}</p> -->
																<input type="text" class="p-1  w-full bg-yellow-50 border-b" :id="'refpono'+ index" v-model="pd.reference_po_no" readonly>
																<input type="text" class="p-1  w-full bg-yellow-50 border-b" :id="'offerdesc'+ index" v-model="pd.offer_desc" readonly>
																<input type="hidden" :id="'refpodetailsid'+ index" v-model="pd.reference_po_details_id">
																<!-- <span class="">{{ pd.offer_desc }}</span> -->
															</div>
														</div>
                                            		</td>
													<td class="border-y-none p-1 text-right">{{formatNumber(pd.unit_price)}} {{ pd.currency }}</td>
													<td class="border-y-none p-1 text-right"> <input type="text" class="text-center tprice" :id="'tprice'+index" v-model="pd.totalprice[index]" readonly></td>
													<td class="p-0" align="center">
														<button class="btn btn-sm btn-danger p-1" @click="removeOffer(index)" v-if="pd.reference_po_no != ''">
															<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
														</button>
														<button class="btn btn-sm btn-primary p-1" @click="openModel(index)">
															<MagnifyingGlassIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></MagnifyingGlassIcon>
														</button>
													</td>
												</tr>
												<tr class="" v-for="(pd, index) in po_details_temp" v-else>
													<span hidden>{{ totalprice=formatNumber(pd.unit_price * pd.quantity) }}</span>
													<td class="border-y-none p-1 text-center">{{ index+1}}</td>
													<td class="border-y-none p-0 text-center">
														{{ pd.quantity }}
														<!-- <input type="number" min="0" @keyup="checkBalance(pd.pr_details_id,pd.quantity, index)" @change="checkBalance(pd.pr_details_id,pd.quantity, index)" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 border-b p-1 text-center" :id="'balance_checker'+index" v-model="pd.quantity"> -->
													</td>
													<td class="border-y-none p-1 text-center">{{ pd.uom }}</td>
													<td class="border-y-none p-1" colspan="2">{{ pd.item_description }}</td>
													<td class="p-1" colspan="2">
														<div class="flex justify-between space-x-1 w-full" >
															<div class="w-full">
																<!-- <p class="w-full text-xs m-0 font-bold">{{ pd.offer_supp }}</p> -->
																<input type="text" class="p-1  w-full bg-yellow-50 border-b" :id="'refpono'+ index" v-model="pd.reference_po_no" readonly>
																<input type="text" class="p-1  w-full bg-yellow-50 border-b" :id="'offerdesc'+ index" v-model="pd.offer_desc" readonly>
																<input type="hidden" :id="'refpodetailsid'+ index" v-model="pd.reference_po_details_id">
																<!-- <span class="">{{ pd.offer_desc }}</span> -->
															</div>
														</div>
                                            		</td>
													<td class="border-y-none p-1 text-right">{{formatNumber(pd.unit_price)}} {{ pd.currency }}</td>
													<td class="border-y-none p-1 text-right"> <input type="text" class="text-center tprice" :id="'tprice'+index" v-model="totalprice[index]" readonly></td>
													<td class="p-0" align="center">
														<button class="btn btn-sm btn-danger p-1" @click="removeOffer(index)" v-if="pd.reference_po_no != ''">
															<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
														</button>
														<button class="btn btn-sm btn-primary p-1" @click="openModel(index)">
															<MagnifyingGlassIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></MagnifyingGlassIcon>
														</button>
													</td>
												</tr>
												<tr class="">
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
												</tr>
												<tr class="">
													<td class="border-r-none align-top p-2" colspan="6" width="65%" rowspan="5">
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>{{po_head.pr_no}}</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>{{pr_head.requestor}}</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>{{pr_head.enduse}}</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>{{pr_head.purpose}}</p>
													</td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
													<td class="p-0">
														<input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-0.5 text-right pr-1" v-model="shipping_cost"  @keyup="additionalCost(vat_percent)" @change="additionalCost(vat_percent)" v-if="po_head.status!='Revised'">

														<input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-0.5 text-right pr-1" :value="po_head_temp.shipping_cost"  @keyup="additionalCost(vat_percent)" @change="additionalCost(vat_percent)" readonly v-else>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
													<td class="p-0">
														<input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" v-model="handling_fee"  @keyup="additionalCost(vat_percent)" @change="additionalCost(vat_percent)" v-if="po_head.status!='Revised'">
														<input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" v-model="po_head_temp.handling_fee"  @keyup="additionalCost(vat_percent)" @change="additionalCost(vat_percent)" readonly v-else>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
													<td class="p-0">
														<input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" v-model="discount"  @keyup="additionalCost(vat_percent)" @change="additionalCost(vat_percent)" v-if="po_head.status!='Revised'">
														<input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" v-model="po_head_temp.discount"  @keyup="additionalCost(vat_percent)" @change="additionalCost(vat_percent)" readonly v-else>
													</td>
												</tr>
												<!-- <tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT</td>
													<td class="p-0">
														<div class="flex">
															<input type="text" class="w-10 bg-white border-r text-center" disabled value="12%">
															<input type="text" class="w-10 bg-white border-r text-center" disabled value="12" hidden>
															<input type="text" class="w-full bg-white p-1 text-right" disabled value="">
														</div>
													</td>
												</tr> -->
												<tr class="">
													<td class="border-l-none border-y-none p-0 text-right" colspan="2">
														<div class="flex justify-end">
															<!-- <span class="p-1" >VAT</span> -->
															<select name="" class="border px-1 text-xs" id="" @change="selectVat(vat_percent)" v-model="vat" v-if="po_head.status!='Revised'">
																<option value="0">--Select--</option>
																<option value="1">VAT</option>
																<option value="2">NON-VAT</option>
															</select>
															<select name="" class="border px-1 text-xs" id="" @change="selectVat(vat_percent)" v-model="po_head_temp.vat" disabled v-else>
																<option value="0">--Select--</option>
																<option value="1">VAT</option>
																<option value="2">NON-VAT</option>
															</select>
														</div>
													</td>
													<!-- Kamo na bahala mag hide sang duwa ka input sa dalom kung Non VAT-->
													<td class="p-0" v-if="vat==1">
														<div class="flex p-0">
															<input type="text" @keypress="isNumber($event)" min="0" class="w-10 bg-yellow-50 border-r text-center" v-model="vat_percent" id="vat_percent" @keyup="vatChange(vat_percent)"  v-if="po_head.status!='Revised'">
															<input type="text" @keypress="isNumber($event)" min="0" class="w-10 bg-yellow-50 border-r text-center" v-model="po_head_temp.vat_percent" id="vat_percent" @keyup="vatChange(vat_percent)" v-else disabled>%
															<input type="text" class="w-10 bg-yellow-50 border-r text-center" value="12" hidden>

															<input type="text" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" id="vat_amount" v-model="vat_amount" @keyup="additionalCost(vat_percent)" @change="additionalCost(vat_percent)" v-if="po_head.status!='Revised'">
															<input type="text" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" id="vat_amount" v-model="po_head_temp.vat_amount" @keyup="additionalCost(vat_percent)" @change="additionalCost(vat_percent)" v-else disabled>
														</div>
													</td>
													<!-- NON-VAT -->
													<td class="p-0" v-else>
														<div class="flex">
															<input type="number" class="w-full bg-white p-1 text-right" id="vat_percent" readonly value="0">
															<input type="hidden" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-center" id="vat_amount" v-model="vat_amount">
														</div>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">
														<span id="grand_total">{{ formatNumber(grand_total) }}</span>
														<input type="hidden" v-model="orig_amount">
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								
								<div class="row mt-2">
									<div class="col-lg-6">
										<table class="table-bordered !text-xs w-full">
											<tr>
												<td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
											</tr>
											<tr>
												<td class="p-0" colspan="2">
													<input type="text" class="p-1 w-full bg-yellow-50" v-model="terms_text" id="check_terms">
												</td>
												<td class="p-0" width="1">
													<button type="button" class="btn btn-primary p-1" @click="addRowTerms">
														<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
													</button>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">1.</td>
												<td class="align-top px-1" colspan="2">PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">2.</td>
												<td class="align-top px-1" colspan="2">Sub-standard items shall be returned to supplier @ no cost to CENPRI.</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">3.</td>
												<td class="align-top pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-14">Price is </span>
														<select name="" class="w-full bg-yellow-50" id="" v-model="vat_in_ex">
															<option value="1">Inclusive of VAT</option>
															<option value="2">Exclusive of VAT</option>
														</select>
													</div>
												</td>
											</tr>
											<tr v-for="(vt,indexterms) in vendor_terms">
												<td class="align-top text-center" width="4%">{{indexterms + 4}}.</td>
												<td class="align-top" colspan="2">
													<div class="flex justify-between">
														<textarea class="w-full bg-yellow-50 px-1" id="" v-model="vt.terms"></textarea>
													</div>
												</td>
												<td v-if="props.id!=0 || pohead_id!=0">
													<button type="button" @click="deleteTerms(vt.id,'no')" class="btn btn-danger p-1">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
											<tr v-for="(t,index) in terms_list">
												<td class="align-top text-center" width="4%">{{ index + 4 +vendor_terms.length }}.</td>
												<td class="px-1" colspan="2">
													<!-- <span class="w-32">{{ t.terms_condition }}</span> -->
													<textarea class="w-full bg-yellow-50 px-1" id="" v-model="t.terms_condition"></textarea>
												</td>
												<td class="p-0 align-top" width="1">
													<button type="button" class="btn btn-danger p-1">
														<XMarkIcon fill="none" @click="removeTerms(index)" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
										</table>
									</div>
									<div class="col-lg-6">
										<table class="table-bordered !text-xs w-full">
											<tr>
												<td class="p-1 uppercase" colspan="3">Other Instructions</td>
											</tr>
											<tr>
												<td class="p-0" colspan="2">
													<input type="text" v-model="other_text" class="p-1 w-full bg-yellow-50" id="check_others">
												</td>
												<td class="p-0" width="1">
													<button type="button" @click="addRowOther" class="btn btn-primary p-1">
														<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
													</button>
												</td>
											</tr>
											<tr v-for="(o, indexes) in other_list">
												<td class="px-1" colspan="2">
													<textarea class="w-full bg-yellow-50 px-1" id="" v-model="o.instructions"></textarea>
												</td>
												<td class="p-0 align-top" width="1">
													<button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1" v-if="o.id == ''">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
													<button type="button" @click="deleteInstructions(o.id)" class="btn btn-danger p-1" v-else>
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="row mt-4 mb-4">
									<div class="col-lg-12">
										<table class="w-full text-xs">
											<tr>
												<td class="text-center" width="20%">Prepared by</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Reviewed/Checked by</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Recommended by</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Approved by</td>
											</tr>
											<tr>
												<td class="text-center border-b"><br></td>
												<td></td>
												<td class="text-center border-b"></td>
												<td></td>
												<td class="text-center border-b"></td>
												<td></td>
												<td class="text-center border-b"></td>
											</tr>
											<tr>
												<td class="text-center p-1"><input type="text" class="text-center">{{prepared_by}}</td>
												<td></td>
												<td class="text-center p-1">
												<select class="text-center bg-yellow-50" v-model="checked_by_id" id="checked_by" @click="resetError('button1')">
													<option value='0'>--Select Reviewed/Checked by--</option>
													<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
												</select>
												</td>
												<td></td>
												<td class="text-center p-1">
												<select class="text-center bg-yellow-50" v-model="recommended_by_id" id="recommended_by" @click="resetError('button2')">
													<option value='0'>--Select Recommended by--</option>
													<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
												</select>
												</td>
												<td></td>
												<td class="text-center p-1">
												<select class="text-center bg-yellow-50" v-model="approved_by_id" id="approved_by" @click="resetError('button3')">
													<option value='0'>--Select Approved by--</option>
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
												<td class="text-right" colspan="2">Conforme: </td>
												<td class="text-center border-b" colspan="3"></td>
												<td class="text-center"></td>
											</tr>
											<tr>
												<td class="text-right" colspan="2"></td>
												<td class="text-center p-1" colspan="3">Signature over Printed Name</td>
												<td class="text-center"></td>
											</tr>
										</table>
									</div>
								</div>
								<hr	class="border-dashed mt-4">
								<div class="row mt-2 po_buttons">
									<div class="col-lg-12">
										<span class="text-xs">Internal Comment</span>
										<textarea name="" id=""  rows="3" class="w-full bg-yellow-50 text-xs border p-1" v-model="po_head.internal_comment"></textarea>
									</div>
								</div>
								<hr	class="border-dashed">
								<div class="po_buttons">
									<div class="!hidden " :class="{ show:approval_set }">
										<div class="row">
											<div class="col-lg-12">
												<div class="flex justify-center">
													<button  class="btn btn-primary w-36" @click="printDiv()">Print</button>
												</div>
											</div>
										</div>
										<div class="row my-2 bg-yellow-50 px-2 py-3"> 
											<div class="col-lg-2 col-md-3 pl-0">
												<span class="text-sm p-1">Approve Date</span>
												<input type="date" v-model="approved_date" class="form-control" id="approved_date" @click="resetError('button1')">
											</div>
											<div class="col-lg-3 col-md-3">
												<span class="text-sm p-1">Approve By</span>
												<select class="form-control" v-model="approved_by_rev" id="approved_by_rev" @click="resetError('button2')">
													<option value='0'>--Select Approve by--</option>
													<option :value="revsig.id" v-for="revsig in signatories" :key="revsig.id">{{ revsig.name }}</option>
												</select>
												<!-- <select class="form-control">
													<option value="">Beverly Espareal</option>
												</select> -->
											</div>
											<div class="col-lg-6 col-md-6">
												<span class="text-sm p-1">Reason</span>
												<textarea name="" class="form-control" rows="1" v-model="approved_reason" ></textarea>
											</div>
											<div class="col-lg-1 col-md-1">
												<span class="text-sm p-1"><br></span>
												<button @click="onSaveApprove()" class="btn btn-primary btn-sm" id="save_approve">Approve</button>
											</div>
										</div>
									</div>
									<div class="" :class="{ hidden:buttons_set }">
										<div class="row my-2" > 
											<div class="col-lg-12 col-md-12">
												<div class="flex justify-center space-x-2">
													<div class="flex justify-between space-x-1">
														<!-- <button type="submit" class="btn btn-warning w-26 !text-white" @click="openWarningAlert()" id="draft">Save as Draft</button> -->
														<button  type="button" class="btn btn-primary w-36"  @click="openInfoAlert()" id="save">Save</button>
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
				</div>
			</div>
		</div>
		<Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal pt-4 px-3" :class="{ show:showModal }">
				<div @click="closeAlert" class="w-full h-full fixed"></div>
				<div class="modal__content w-10/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Search Item</span>
							<a href="#" class="text-gray-600" @click="closeAlert">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
                        <div class="row">
							<div class="col-lg-5 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Item Description</label>
                                    <input class="w-full text-sm p-1 px-2 border" placeholder="Item Description" v-model="item_description">
								</div>
							</div>
							<!-- <div class="col-lg-6 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0 " for="">Supplier</label>
									<select class="w-full text-sm p-1 px-2 border">
                                        <option value="Bacolod Triumph Hardware (Main Branch)">Bacolod Triumph Hardware (Main Branch)</option>
                                        <option value="Bacolod Mindanao Lumber and Plywood Corp.">Bacolod Mindanao Lumber and Plywood Corp.</option>
                                        <option value="SGS Hardware Corporation">SGS Hardware Corporation</option>
                                        <option value="Bacolod Paint Marketing">Bacolod Paint Marketing</option>
                                        <option value="New China Enterprise Inc.">New China Enterprise Inc.</option>
                                        <option value="United Bearing Industrial Corp">United Bearing Industrial Corp</option>
                                    </select>
								</div>
							</div> -->
							<div class="col-lg-1">
								<br>
								<button class="btn btn-sm btn-primary" type="button" @click="getPOItems()">Search</button>
							</div>
						</div>
						<div class="row" style="display:none" id="repeat_po_items">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mb-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase" width="20%">PO Number</td>
										<td class="p-1 uppercase" width="30%">Item Description</td>
										<td class="p-1 uppercase" width="30%">Unit Price</td>
										<!-- <td class="p-1 uppercase" width="30%">Supplier</td> -->
                                        <td class="p-1" align="center" width="1%">
                                            <span>
                                                <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></PlusIcon>
                                            </span>
                                        </td>
									</tr>
									<tr v-for="(i, index) in item_list" v-if="item_list.length != 0">
										<td class="p-1">{{ i.po_no }}</td>
										<td class="p-1" :id="'ritem_description'+ index">{{ i.item_description }}</td>
										<td class="p-1">{{ parseFloat(i.unit_price).toFixed(4) }}</td>
										<input type="hidden" :id="'rpodetailsid'+ index" v-model="i.po_details_id">
										<input type="hidden" :id="'rpono'+ index" v-model="i.po_no">
										<input type="hidden" :id="'runitprice'+ index" v-model="i.unit_price">
										<input type="hidden" :id="'offercurrency'+ index" v-model="i.offer_currency">
                                        <td class="p-0 text-center">
                                            <button class="btn btn-sm btn-primary p-1" @click="addRepeatItem(index)">
                                                <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
                                            </button>
                                        </td>
									</tr>
									<td class="p-1" align="center" colspan="4" v-else>
										<center><span><b>No Available Data...</b></span></center>
									</td>
								</table>
							</div>
						</div>
						<!-- <div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="" class="btn btn-primary mr-2 w-44">Add</a>
								</div>
							</div>
						</div> -->
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
			<div class="modal p-0 !bg-transparent" :class="{ show:infoAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-blue-500 border-8 bg-blue-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></ExclamationTriangleIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-blue-400">Confirm</h2>
									<h5 class="leading-tight">Are you sure you want to revise this PO?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<button @click="onSave()" class="btn !bg-blue-500 !text-white btn-sm !rounded-full w-full" id="confirm_alert">Save</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:approveAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-blue-500 border-8 bg-blue-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></ExclamationTriangleIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-blue-400">Confirm</h2>
									<h5 class="leading-tight">Are you sure you want to approve this revision?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">No</button>
									<a href="/po_repeat/view" class="btn !bg-blue-500 !text-white btn-sm !rounded-full w-full">Yes</a>
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
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">You have successfully saved a Revised PO as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<!-- <a href="/pur_po/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a> -->
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_terms }">
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
									<h5 class="leading-tight">Are you sure you want to remove this term?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="deleteTerms(terms_id,'yes')" >Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_instructions }">
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
									<h5 class="leading-tight">Are you sure you want to remove this instruction?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="deleteInstructions(instruction_id,'yes')" >Yes</button>
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
	</navigation>
	
</template>