<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	const router = useRouter();
	const preview =  ref();
	const error =  ref('');
	const success =  ref('');
	const jorno_dropdown =  ref([]);
	const suppliers =  ref([]);
	const dangerAlert = ref(false)
	const dangerAlerterrors = ref(false)
	const successAlert = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
	const dangerAlert_terms = ref(false)
	const dangerAlert_instructions = ref(false)
	const successAlertCD = ref(false)
	const vendor_details_id =  ref('');
	const jor_no =  ref('');
	const date_needed =  ref('');
	const completion_work =  ref('');
	const date_prepared =  ref('');
	const start_of_work =  ref('');
	const dr_no=ref('');
	const joi_no=ref('');
	const joi_head_id=ref(0);
	const joi_head=ref([]);
	const jor_head=ref([]);
	const joi_labor_details=ref([]);
	const joi_material_details=ref([]);
	const jo_rfq_terms=ref([]);
	const vendor=ref([]);
	const orig_labor_amount=ref(0);
	const grand_labor_total=ref(0);
	const grand_material_total=ref(0);
	const prepared_by=ref('');
	const checked_by=ref(0);
	const recommended_by=ref(0);
	const approved_by=ref(0);
	const remaining_labor_balance=ref([])
	const remaining_material_balance=ref([])
	const vat=ref(0);
	const vat_in_ex=ref(0);
	const vat_amount=ref(0);
	const discount_labor=ref(0);
	const discount_material=ref(0);
	const new_data =  ref(0);
	const overall_total=ref(0)
	const signatories=ref([])
	const conforme=ref('')
	const newvat=ref(0);
	const instruction_id=ref(0);
	const terms_id=ref(0);
	const cancel_all_reason=ref('');
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		getSupplier()
		getSignatories()
		if(props.id!=0){
			joDraft()
		}
	})

	const joDraft = async () => {
		let response = await axios.get("/api/jo_viewdetails/"+props.id);
		joi_head_id.value=props.id
		prepared_by.value = response.data.prepared_by;
		checked_by.value = response.data.joi_head.checked_by;
		recommended_by.value = response.data.joi_head.recommended_by;
		approved_by.value = response.data.joi_head.approved_by;
		joi_head.value = response.data.joi_head;
		conforme.value = response.data.joi_head.conforme;
		joi_no.value = response.data.joi_head.joi_no;
		dr_no.value = response.data.joi_dr.dr_no;
		discount_labor.value = response.data.joi_head.discount;
		discount_material.value = response.data.joi_head.discount_material;
		vat.value = response.data.joi_head.vat;
		vat_amount.value = response.data.joi_head.vat_amount;
		vat_in_ex.value = response.data.joi_head.vat_in_ex;
		newvat.value= (response.data.grand_labor_total + response.data.grand_material_total) * (vat.value/100)
		grand_labor_total.value = response.data.grand_labor_total.toFixed(2);
		grand_material_total.value = response.data.grand_material_total;
		overall_total.value=(response.data.grand_labor_total + response.data.grand_material_total + newvat.value) - (discount_labor.value + discount_material.value)
		jor_head.value = response.data.jor_head;
		vendor.value = response.data.joi_vendor;
		joi_labor_details.value = response.data.joi_labor_details;
		joi_material_details.value = response.data.joi_material_details;
		jo_rfq_terms.value = response.data.joi_terms;
		other_list.value = response.data.joi_instructions;
	}

	const joDraftDisplay = async () => {
		let response = await axios.get("/api/jo_viewdetails/"+joi_head_id.value);
		jo_rfq_terms.value = response.data.joi_terms;
		other_list.value = response.data.joi_instructions;
	}
	const formatNumber = (number) => {
      return number.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

	const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
	}

	const getSupplier = async () => {
		let response = await axios.get("/api/jo_supplier_dropdown");
		suppliers.value = response.data.suppliers;
	}

	const getSupplierJOR = async () => {
		let response = await axios.get("/api/get_jorno/"+vendor_details_id.value);
		jorno_dropdown.value = response.data.jorno_dropdown;
		// po_head.value=[]
	}
	const openDangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
    const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}

	const openWarningAlert = () => {
		warningAlert.value = !warningAlert.value
	}
	const closeAlert = () => {
		successAlertCD.value = !hideAlert.value
		dangerAlert_terms.value = !hideAlert.value
		dangerAlert_instructions.value = !hideAlert.value
		successAlert.value = !hideAlert.value
		dangerAlerterrors.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		warningAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
	}
	const showAddVendor = ref(false)
	const showPreview = ref(false)
	const hideModal = ref(true)
	const openAddVendor = () => {
		showAddVendor.value = !showAddVendor.value
	}
	const openPreview = () => {
		showPreview.value = !showPreview.value
	}
	const closeModal = () => {
		showAddVendor.value = !hideModal.value
		showPreview.value = !hideModal.value
	}

	
	const jor_det = ref(false)

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
			document.getElementById('check_terms').style.backgroundColor = '#FFFFFF';
		}else{
			document.getElementById('check_terms').placeholder="Please fill in Terms and Condition."
			document.getElementById('check_terms').style.backgroundColor = '#FAA0A0';
		}
	}
	const removeTerms = (index) => {
		terms_list.value.splice(index,1)
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
			document.getElementById('check_others').style.backgroundColor = '#FFFFFF';
		}else{
			document.getElementById('check_others').placeholder="Please fill in Other instructions."
			document.getElementById('check_others').style.backgroundColor = '#FAA0A0';
		}
	}
	const removeOthers = (index) => {
		other_list.value.splice(index,1)
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
		var total=0;
		var totalm=0;
		joi_labor_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			total += parseFloat(pi);
        });
		joi_material_details.value.forEach(function (val, index, theArray) {
			var pm = document.getElementById('tmprice'+index).value;
			var pmi = pm.replace(",", "");
			totalm += parseFloat(pmi);
        });
		// var vat_percent = document.getElementById("vat_percent").value;
		var percent=vat_percent/100;
		var new_vat= ((parseFloat(total) + parseFloat(totalm)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value))) * percent;

		var new_total = (parseFloat(total) + parseFloat(totalm) + parseFloat(new_vat)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value));
		// var new_total = (parseFloat(total) + parseFloat(totalm) + parseFloat(new_vat)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value));
		document.getElementById("grand_labor_total").innerHTML  = new_total.toFixed(2)
		document.getElementById("overalltotal").innerHTML  = new_total.toFixed(2)
		new_data.value=parseFloat(new_total)
		document.getElementById("vat_amount").value=new_vat.toFixed(2);
		vat_amount.value=new_vat.toFixed(2);
	}

	const vatChange = (vat_percent) => {
		// var total = (orig_amount.value==0) ? grand_total.value : orig_amount.value;
		var total=0;
		var totalm=0;
		joi_labor_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			total += parseFloat(pi);
        });
		joi_material_details.value.forEach(function (val, index, theArray) {
			var pm = document.getElementById('tmprice'+index).value;
			var pmi = pm.replace(",", "");
			totalm += parseFloat(pmi);
        });
		// var vat_percent = document.getElementById("vat_percent").value;
		// var vat_percent = vat_percent.value;
		// alert(vat_percent)
        var percent=vat_percent/100;
        var new_vat = ((parseFloat(total) + parseFloat(totalm)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value))) * parseFloat(percent);
        vat_amount.value = new_vat.toFixed(2);
        // document.getElementById("vat_amount").value = new_vat.toFixed(2);
        var new_total=(parseFloat(total) + parseFloat(totalm) + parseFloat(new_vat)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value));
        document.getElementById("grand_labor_total").innerHTML=new_total.toFixed(2);
        document.getElementById("overalltotal").innerHTML=new_total.toFixed(2);
		new_data.value=parseFloat(new_total)
	} 

	const checkLaborBalance = async (jor_labor_details_id,jo_rfq_labor_offer_id,vat_percent,qty,count) => {
		var grandlabortotal=0;
		var grandmaterialtotal=0;
		joi_labor_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			grandlabortotal += parseFloat(pi);
        });
		joi_material_details.value.forEach(function (val, index, theArray) {
			var pm = document.getElementById('tmprice'+index).value;
			var pim = pm.replace(",", "");
			grandmaterialtotal += parseFloat(pim);
        });
		// var vat = document.getElementById("vat_percent").value;
		// alert(vat_percent)
        var percent=vat_percent/100;
		var new_vat = ((parseFloat(grandlabortotal) + parseFloat(grandmaterialtotal)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value))) * parseFloat(percent);
		vat_amount.value=new_vat.toFixed(2);
		var overall_total = (parseFloat(grandlabortotal) + parseFloat(grandmaterialtotal) + parseFloat(new_vat)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value));
		var labor_total = parseFloat(grandlabortotal);
		var material_total = parseFloat(grandmaterialtotal);
		grand_labor_total.value=labor_total.toFixed(2);
		grand_material_total.value=material_total.toFixed(2);
		// grand_total.value=grandlabortotal + new_vat);
		orig_labor_amount.value=grandlabortotal.toFixed(2);
		document.getElementById("grand_labor_total").innerHTML=overall_total.toFixed(2);
		document.getElementById("overalltotal").innerHTML=overall_total.toFixed(2);
		let response = await axios.get("/api/check_labor_balance/"+jor_labor_details_id+'/'+jo_rfq_labor_offer_id);
		// balance.value = response.data.balance;
		// var po_qty=balance.value.po_qty + balance.value.dpo_qty + balance.value.rpo_qty
		var all_qty=response.data.total_sum_pr_labor - response.data.total_sum_labor
		if(qty==0){
			document.getElementById('balance_labor_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = true;
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else if(qty>all_qty){
			document.getElementById('balance_labor_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = true;
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_labor_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = false;
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}

	const checkMaterialBalance = async (jor_material_details_id,jo_rfq_material_offer_id,vat_percent,qty,count) => {
		var grandlabortotal=0;
		var grandmaterialtotal=0;
		joi_labor_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			grandlabortotal += parseFloat(pi);
        });
		joi_material_details.value.forEach(function (val, index, theArray) {
			var pm = document.getElementById('tmprice'+index).value;
			var pim = pm.replace(",", "");
			grandmaterialtotal += parseFloat(pim);
        });
		// var vat = document.getElementById("vat_percent").value;
		// alert(vat_percent)
        var percent=vat_percent/100;
		var new_vat = ((parseFloat(grandlabortotal) + parseFloat(grandmaterialtotal)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value))) * parseFloat(percent);
		vat_amount.value=new_vat.toFixed(2);
		var overall_total = (parseFloat(grandlabortotal) + parseFloat(grandmaterialtotal) + parseFloat(new_vat)) - (parseFloat(discount_labor.value) + parseFloat(discount_material.value));
		var labor_total = parseFloat(grandlabortotal);
		var material_total = parseFloat(grandmaterialtotal);
		grand_labor_total.value=labor_total.toFixed(2);
		grand_material_total.value=material_total.toFixed(2);
		// grand_total.value=grandlabortotal + new_vat);
		orig_labor_amount.value=grandlabortotal.toFixed(2) + grandmaterialtotal.toFixed(2);
		document.getElementById("grand_labor_total").innerHTML=overall_total.toFixed(2);
		document.getElementById("overalltotal").innerHTML=overall_total.toFixed(2);
		let response = await axios.get("/api/check_material_balance/"+jor_material_details_id+'/'+jo_rfq_material_offer_id);
		// balance.value = response.data.balance;
		// var po_qty=balance.value.po_qty + balance.value.dpo_qty + balance.value.rpo_qty
		var all_qty=response.data.total_sum_pr_material - response.data.total_sum_material
		if(qty==0){
			document.getElementById('balance_material_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = true;
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else if(qty>all_qty){
			document.getElementById('balance_material_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = true;
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_material_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = false;
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}

	const checkLaborRemainingQty = async (jor_labor_details_id,jo_rfq_labor_offer_id,qty,count) => {
		let response = await axios.get("/api/check_labor_balance/"+jor_labor_details_id+"/"+jo_rfq_labor_offer_id);
		remaining_labor_balance.value[count] = qty - response.data.total_sum_labor;
	}

	const checkMaterialRemainingQty = async (jo_material_details_id,jo_rfq_material_offer_id,qty,count) => {
		let response = await axios.get("/api/check_material_balance/"+jo_material_details_id+'/'+jo_rfq_material_offer_id);
		remaining_material_balance.value[count] = qty - response.data.total_sum_material;
	}

	const generateJO = async () => {
		let response = await axios.get("/api/generate_joi/"+vendor_details_id.value+'/'+jor_no.value);
		dr_no.value = response.data.dr_no;
		joi_no.value = response.data.joi_no;
		joi_head.value = response.data.joi_head;
		jor_head.value = response.data.jor_head;
		joi_labor_details.value = response.data.joi_labor_details;
		joi_material_details.value = response.data.joi_material_details;
		jo_rfq_terms.value = response.data.jo_rfq_terms;
		vendor.value = response.data.vendor;
		grand_labor_total.value = response.data.grand_labor_total.toFixed(2);
		grand_material_total.value = response.data.grand_material_total.toFixed(2);
		overall_total.value=response.data.grand_labor_total + response.data.grand_material_total
		prepared_by.value = response.data.prepared_by;
		joi_labor_details.value.forEach(function (val, index, theArray) {
			checkLaborRemainingQty(val.jor_labor_details_id,val.id,val.jor_labor_details.quantity,index)
		});
		joi_material_details.value.forEach(function (vals, index, theArray) {
			checkMaterialRemainingQty(vals.jor_material_details_id,vals.id,vals.jor_material_details.quantity,index)
		});
	}

	const deleteJOTerms = (id,option) => {
		if(option=='yes'){
			axios.get(`/api/delete_jo_terms/`+id).then(function () {
				dangerAlert_terms.value = !hideAlert.value
				success.value='Successfully deleted term!'
				successAlertCD.value = !successAlertCD.value
				joDraftDisplay()
				terms_list.value=[]
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			terms_id.value=id
			dangerAlert_terms.value = !dangerAlert_terms.value
		}
	}

	const deleteInstructions = (id,option) => {
		if(option=='yes'){
			axios.get(`/api/delete_jo_instructions/`+id).then(function () {
				dangerAlert_instructions.value = !hideAlert.value
				success.value='Successfully deleted instruction!'
				successAlertCD.value = !successAlertCD.value
				joDraftDisplay()
				other_list.value=[]
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			instruction_id.value=id
			dangerAlert_instructions.value = !dangerAlert_instructions.value
		}
	}

	const onSave = (status) => {
		const formData= new FormData()
		var total = document.querySelector("#grand_labor_total").textContent;
		var total_replace = total.replace(",", "");
		formData.append('date_needed', date_needed.value)
		formData.append('completion_work', jor_head.value.date_prepared)
		formData.append('date_prepared', jor_head.value.date_prepared)
		formData.append('start_of_work', start_of_work.value)
		formData.append('dr_no', dr_no.value)
		formData.append('joi_no', joi_no.value)
		formData.append('jor_no', jor_head.value.jor_no)
		formData.append('vendor_details_id', vendor.value.id)
		formData.append('discount_labor', discount_labor.value)
		formData.append('discount_material', discount_material.value)
		formData.append('vat', vat.value)
		formData.append('vat_amount', vat_amount.value)
		formData.append('vat_in_ex', vat_in_ex.value)
		formData.append('grand_total', total_replace)
		formData.append('checked_by', checked_by.value)
		formData.append('approved_by', approved_by.value)
		formData.append('recommended_by', recommended_by.value)
		formData.append('conforme', conforme.value)
		formData.append('terms_list', JSON.stringify(terms_list.value))
		formData.append('jo_rfq_terms', JSON.stringify(jo_rfq_terms.value))
		formData.append('other_list', JSON.stringify(other_list.value))
		formData.append('joi_labor_details', JSON.stringify(joi_labor_details.value))
		formData.append('joi_material_details', JSON.stringify(joi_material_details.value))
		formData.append('joi_head_id', joi_head_id.value)
		formData.append('props_id', props.id)
		formData.append('status', status)
		joi_labor_details.value.forEach(function (val, index, theArray) {
			formData.append('quantity_labor'+index, remaining_labor_balance.value[index])
		});
		joi_material_details.value.forEach(function (val, indexer, theArray) {
			formData.append('quantity_material'+indexer, remaining_material_balance.value[indexer])
		});
		if(status==='Saved'){
			if(checked_by.value!=0 && approved_by.value!=0 && recommended_by.value!=0){
				axios.post(`/api/save_joi`,formData).then(function (response) {
					joi_head_id.value=response.data;
					success.value='You have successfully saved new jo.'
					successAlertCD.value=!successAlertCD.value
					setTimeout(() => {
						if(props.id==0){
							router.push('/job_issue/view/'+joi_head_id.value)
						}else{
							router.push('/job_issue/view/'+props.id)
						}
					}, 2000);
				}, function (err) {
					// error.value = err.response.data.message;
					error.value='Error! Please try again.';
					dangerAlerterrors.value=!dangerAlerterrors.value
				}); 
			}else{
				if(checked_by.value==0){
					document.getElementById('checked_by').style.backgroundColor = '#FAA0A0';
				}
				if(approved_by.value==0){
					document.getElementById('approved_by').style.backgroundColor = '#FAA0A0';
				}
				if(recommended_by.value==0){
					document.getElementById('recommended_by').style.backgroundColor = '#FAA0A0';
				}
				const btn_draft = document.getElementById("draft");
				btn_draft.disabled = true;
				const btn_save = document.getElementById("save");
				btn_save.disabled = true;
			}
		}else if(status==='Draft'){
			axios.post(`/api/save_joi`,formData).then(function (response) {
				joi_head_id.value=response.data;
				success.value='You have successfully draft new jo.'
				warningAlert.value=!warningAlert.value
				if(props.id!=0){
					joDraft()
				}else{
					terms_list.value=[]
					other_list.value=[]
					joDraftDisplay()
				}
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
		if(button==='button3'){
			document.getElementById('approved_by').style.backgroundColor = '#FEFCE8';
		}
		if(button==='button2'){
			document.getElementById('recommended_by').style.backgroundColor = '#FEFCE8';
		}
		const btn_draft = document.getElementById("draft");
		btn_draft.disabled = false;
		const btn_save = document.getElementById("save");
		btn_save.disabled = false;
	}

	const allZeroLabor = () => {
		if(props.id==0){
      		return remaining_labor_balance.value.every(value => value === 0);
		}else{
			return false;
		}
    }

	const allZeroMaterial = () => {
		if(props.id==0){
      		return remaining_material_balance.value.every(value => value === 0);
		}else{
			return false;
		}
    }
	const cancelAllJO = (option) => {
		let id = (props.id==0) ? joi_head_id.value : props.id
		if(option=='yes'){
			if(cancel_all_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_all_reason', cancel_all_reason.value)
				axios.post(`/api/cancel_all_jo/`+id,formData).then(function (response) {
                    dangerAlert.value = !hideAlert.value
                    success.value='Successfully cancelled PO!'
                    successAlertCD.value = !successAlertCD.value
                    cancel_all_reason.value=''
                    document.getElementById('cancel_all_check').placeholder=""
                    document.getElementById('cancel_all_check').style.backgroundColor = '#FFFFFF';
                    joDraft()
                    router.push('/pur_po/view/'+id)
				})
			}else{
				document.getElementById('cancel_all_check').placeholder="Cancel Reason must not be empty!"
				document.getElementById('cancel_all_check').style.backgroundColor = '#FAA0A0';
			}
		}else{
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Issuance <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_issue">JO Issuance</a></li>
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
						<div class="row">							
							<div class="col-lg-8 offset-lg-2 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Choose Supplier and JOR No</label>
									<input type="file" name="img[]" class="file-upload-default">
									<div class="input-group col-xs-12">
										<select class="form-control file-upload-info" @change="getSupplierJOR()" v-model="vendor_details_id">
											<option value="">--Select Supplier--</option>
											<option :value="sup.id" v-for="sup in suppliers" :key="sup.id">{{ sup.vendor_name }} ({{ sup.identifier }})</option>
										</select>
										<select class="form-control file-upload-info" v-model="jor_no">
											<option value="">--Select JOR Number--</option>
											<option :value="j.jor_no+'+'+j.id" v-for="j in jorno_dropdown" :key="j.jor_no+'+'+j.id">{{ j.jor_no }} ({{ j.aoq_date }} - {{ j.id }})</option>
										</select>
										<span class="input-group-append">
											<button class="btn btn-primary" type="button" @click="generateJO()">Select</button>
											<!-- <button class="btn btn-primary" type="button" @click="jor_det = !jor_det">Select</button> -->
										</span>
									</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div class="pt-1">
							<div v-if="joi_head && joi_head.length!=0 && (!allZeroLabor() || !allZeroMaterial())">
								<!-- <div v-show="jor_det"> -->
								<div class="row">
									<div class="col-lg-1">
										<span class="text-sm">TO:</span>
									</div>
									<div class="col-lg-11">
										<p class="m-0 font-bold capitalize">{{ vendor.vendor_name }} ({{ vendor.identifier }})</p>
										<p class="m-0">{{vendor.contact_person}}</p>
										<p class="m-0">{{vendor.address}}</p>
										<p class="m-0">{{vendor.phone}}</p>
									</div>
								</div>
								<hr class="border-dashed">
								<div class="row">
									<div class="col-lg-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Needed: </span>
											<input type="date" class="border-b w-full text-sm" v-model="date_needed">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">Completion of Work: </span>
											<input type="date" class="border-b w-full text-sm" v-model="jor_head.completion_date">
										</div>	
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Prepared: </span>
											<input type="date" class="border-b w-full text-sm" v-model="jor_head.date_prepared">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">CENPRI JOR No: </span>
											<input type="text" class="border-b w-full text-sm" v-model="jor_head.site_jor" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Start of Work: </span>
											<input type="date" class="border-b w-full text-sm" v-model="start_of_work">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">JO No: </span>
											<input type="text" class="border-b w-full text-sm" v-model="joi_no" readonly>
										</div>
									</div>
								</div>
								<div class="" >
									<br>
									<div class="row">
										<div class="col-lg-12">
											<div class="border-2">
												<table class="table-bordered w-full !text-xs">
													<tr class="!border-b-3">
														<td colspan="7" class="py-2">
															<p class="text-sm font-bold text-gray-600 text-center m-0">{{jor_head.project_activity}}</p>
															<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
														</td>
													</tr>
													<tr class="bg-gray-100">
														<td class="uppercase p-1" colspan="3">Scope of Work</td>
														<td class="uppercase p-1 text-center" width="7%">Qty</td>
														<td class="uppercase p-1 text-center" width="7%">Unit</td>
														<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
														<td class="uppercase p-1 text-center" width="10%">Total</td>
													</tr>
													<tr>
														<td colspan="7" class="px-1 !text-sm"><span class="font-bold">{{ jor_head.general_description}} </span></td>
													</tr>
													<tr class="" v-for="(jld,index) in joi_labor_details" v-if="props.id == 0">
														<span hidden>{{ totalprice=formatNumber(jld.unit_price * remaining_labor_balance[index]) }}</span>
														<td class="border-y-none p-1" colspan="3">{{ jld.offer }}</td>
														<td class="border-y-none p-0 text-center">
															<input type="text" @keypress="isNumber($event)" @keyup="checkLaborBalance(jld.jor_labor_details_id,jld.id,vat,remaining_labor_balance[index], index)" :id="'balance_labor_checker'+index" class="w-full bg-yellow-50 border-b p-1 text-center" v-model="remaining_labor_balance[index]">
														</td>
														<td class="border-y-none p-1 text-center">{{jld.uom}}</td>
														<td class="border-y-none p-1 text-right">{{jld.unit_price}} {{ jld.currency }}</td>
														<td class="border-y-none p-1 text-right"><input type="text" class="text-center tprice" :id="'tprice'+index" v-model="totalprice" readonly></td>
													</tr>
													<tr class="" v-for="(jld,index) in joi_labor_details" v-else>
														<span hidden>{{ totalprice=formatNumber(jld.unit_price * jld.quantity) }}</span>
														<td class="border-y-none p-1" colspan="3">{{ jld.item_description }}</td>
														<td class="border-y-none p-0 text-center">
															<input type="text" @keypress="isNumber($event)" @keyup="checkLaborBalance(jld.jor_labor_details_id,jld.id,vat,jld.quantity, index)" :id="'balance_labor_checker'+index" class="w-full bg-yellow-50 border-b p-1 text-center" v-model="jld.quantity">
														</td>
														<td class="border-y-none p-1 text-center">{{jld.uom}}</td>
														<td class="border-y-none p-1 text-right">{{jld.unit_price}} {{ jld.currency }}</td>
														<td class="border-y-none p-1 text-right"><input type="text" class="text-center tprice" :id="'tprice'+index" v-model="totalprice" readonly></td>
													</tr>
													<tr class="bg-gray-100">
														<td class="p-1 text-center" width="3%">#</td>
														<td class="p-1" colspan="2">Materials:</td>
                                                        <td class="uppercase p-1 text-center" width="7%">Qty</td>
														<td class="uppercase p-1 text-center" width="7%">Unit</td>
														<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
														<td class="uppercase p-1 text-center" width="10%">Total</td>
													</tr>
													<tr class="" v-for="(jml,indexes) in joi_material_details" v-if="props.id==0">
														<span hidden>{{ totalmprice=formatNumber(jml.unit_price * remaining_material_balance[indexes]) }}</span>
														<td class="border-y-none p-1 text-center">{{indexes+1}}</td>
														<td class="border-y-none p-1" colspan="2">{{jml.offer}}</td>
														<td class="border-y-none p-0 text-center">
															<input type="text" @keypress="isNumber($event)" @keyup="checkMaterialBalance(jml.jor_material_details_id,jml.id,vat,remaining_material_balance[indexes], indexes)" :id="'balance_material_checker'+indexes" class="w-full bg-yellow-50 border-b p-1 text-center" v-model="remaining_material_balance[indexes]">
														</td>
														<td class="border-y-none p-1 text-center">{{jml.uom}}</td>
														<td class="border-y-none p-1 text-right">{{ jml.unit_price }} {{ jml.currency }}</td>
														<td class="border-y-none p-1 text-right"><input type="text" class="text-center tmprice" :id="'tmprice'+indexes" v-model="totalmprice" readonly></td>
													</tr>
													<tr class="" v-for="(jml,indexes) in joi_material_details" v-else>
														<span hidden>{{ totalmprice=formatNumber(jml.unit_price * jml.quantity) }}</span>
														<td class="border-y-none p-1 text-center">{{indexes+1}}</td>
														<td class="border-y-none p-1" colspan="2">{{jml.item_description}}</td>
														<td class="border-y-none p-0 text-center">
															<input type="text" @keypress="isNumber($event)" @keyup="checkMaterialBalance(jml.jor_material_details_id,jml.id,vat,jml.quantity, indexes)" :id="'balance_material_checker'+indexes" class="w-full bg-yellow-50 border-b p-1 text-center" v-model="jml.quantity">
														</td>
														<td class="border-y-none p-1 text-center">{{jml.uom}}</td>
														<td class="border-y-none p-1 text-right">{{ jml.unit_price }} {{ jml.currency }}</td>
														<td class="border-y-none p-1 text-right"><input type="text" class="text-center tmprice" :id="'tmprice'+indexes" v-model="totalmprice" readonly></td>
													</tr>
													<tr class="">
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
													</tr>
													<tr class="">
														<td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="6">
															<!-- <p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">JOR Number:</span>{{ jor_head.jor_no }}</p>
															<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Requestor:</span>{{ jor_head.requestor }}</p>
															<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">End-use:</span>{{ jor_head.enduse }}</p>
															<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p> -->
														</td>
														<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Total Labor</td>
														<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-0.5 text-right pr-1" v-model="grand_labor_total" readonly></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Total Materials</td>
														<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right" v-model="grand_material_total" readonly></td>
													</tr>
													
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Labor</td>
														<td class="p-0"><input type="text" @keypress="isNumber($event)" @keyup="additionalCost(vat)" class="w-full bg-yellow-50 p-1 text-right" v-model="discount_labor"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Material</td>
														<td class="p-0"><input type="text" @keypress="isNumber($event)" @keyup="additionalCost(vat)" class="w-full bg-yellow-50 p-1 text-right" v-model="discount_material"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
														<td class="p-0">
															<div class="flex">
																<input type="text" @keypress="isNumber($event)" @keyup="vatChange(vat)" class="w-10 bg-yellow-50 border-r text-center" v-model="vat">%
																<input type="text" @keypress="isNumber($event)" @keyup="additionalCost(vat)" class="w-full bg-yellow-50 p-1 text-right" v-model="vat_amount" id="vat_amount">
															</div>
														</td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">
															<input type="hidden" v-model="orig_labor_amount">
															GRAND 
														</td>
														<td class="p-1 text-right font-bold !text-sm" id="grand_labor_total">{{overall_total.toFixed(2)}}</td>
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
													<td class="align-top px-1" colspan="2">JO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.</td>
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
															<select class="w-full bg-yellow-50" id="" v-model="vat_in_ex">
																<option value="1">Inclusive of VAT</option>
																<option value="2">Exclusive of VAT</option>
															</select>
														</div>
													</td>
												</tr>
												<tr v-for="(jrt,indexterms) in jo_rfq_terms">
													<td class="align-top text-center" width="4%">{{indexterms + 4}}.</td>
													<td class="align-top" colspan="1">
														<div class="flex justify-between">
															<textarea class="w-full bg-yellow-50 px-1" id="" v-model="jrt.terms"></textarea>
														</div>
													</td>
													<td v-if="props.id!=0 || joi_head_id!=0">
														<button type="button" @click="deleteJOTerms(jrt.id,'no')" class="btn btn-danger p-1">
															<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
														</button>
													</td>
												</tr>
												<tr v-for="(t,indexe) in terms_list">
													<td class="align-top text-center" width="4%">{{ indexe + 4 +jo_rfq_terms.length }}.</td>
													<td class="px-1" colspan="1">
														<span class="w-32">{{ t.terms_condition }}</span>
													</td>
													<td class="p-0 align-top" width="1">
														<button type="button" class="btn btn-danger p-1">
															<XMarkIcon fill="none" @click="removeTerms(indexe)" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
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
													<td class="px-1" colspan="2">{{ o.instructions }}</td>
													<td class="p-0 align-top" width="1">
														<!-- <button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1">
															<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
														</button> -->
														<button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1" v-if="props.id==0 || pohead_id==0">
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
								</div>
								<br>
								<div class="row">
									<div class="col-lg-12">
										<table class="w-full ">
											<tr>
												<td></td>
												<td width="12%" class="font-bold text-sm text-gray-500"> Total Project Cost:</td>
												<td  width="20%" class="border-b border-gray-400 px-4 font-bold text-base text-gray-500"> 
													<div class="flex justify-between  text-lg">
														<span></span>
														<span id="overalltotal">{{overall_total.toFixed(2)}}</span>
													</div>
												</td>
												<td width="14%"></td>
												<td width="8%" class="font-bold text-sm text-gray-500">Conforme:</td>
												<td  width="30%" class="border-b border-gray-400 px-4"><input type="text" class="w-full text-center text-sm capitalize" v-model="conforme"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td class="text-xs text-center">Contractor's Signature Over Printed Name</td>
												<td></td>
											</tr>
										</table>
									</div>
								</div>
								<br>
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
												<td class="text-center p-1">{{prepared_by}}</td>
												<td></td>
												<td class="text-center p-1">
													<select class="text-center bg-yellow-50" v-model="checked_by" id="checked_by" @click="resetError('button1')">
															<option value='0'>--Select Reviewed/Checked by--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
												</td>
												<td></td>
												<td class="text-center p-1">
													<select class="text-center bg-yellow-50" v-model="recommended_by" id="recommended_by" @click="resetError('button2')">
														<option value='0'>--Select Recommended by--</option>
														<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
													</select>
												</td>
												<td></td>
												<td class="text-center p-1">
													<select class="text-center bg-yellow-50" v-model="approved_by" id="approved_by" @click="resetError('button3')">
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
												<td class="text-right" colspan="2">Work Completion Verified by: </td>
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
								<hr	class="border-dashed">
								<div class="row my-2"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-center space-x-2">
											<!-- <div class="flex justify-between space-x-1">
												<button type="submit" @click="openPreview()" class="btn btn-info w-26">Preview</button>
												<button type="submit" @click="openAddVendor()" class="btn btn-info w-26">Add Vendor</button>
											</div> -->
											<div class="flex justify-between space-x-1">
												<!-- kung wala pa na save -->
												<!-- <button type="submit" class="btn btn-primary w-26">Back</button> -->
												<button type="button" class="btn btn-danger w-36"  @click="cancelAllJO('no')" v-if="joi_head.status!='Cancelled' && (props.id!=0 || joi_head_id!=0)">Cancel JO</button>
												<button type="button"  class="btn btn-warning w-26 !text-white" id="draft" @click="onSave('Draft')">Save as Draft</button>
												<button  type="button" class="btn btn-primary w-36" id="save" @click="onSave('Saved')">Save</button>
											</div>
											
										</div>
									</div>
								</div>
							</div>
							<div v-else-if="(remaining_labor_balance.length!=0 || remaining_material_balance.length!=0) && (allZeroLabor() || allZeroMaterial())">
								<center><span><b>No Available Data...</b></span></center>
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
									<h5 class="leading-tight">You have successfully created a new JOI.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/job_issue/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a>
									<a href="/job_issue/view" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a>
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
									<h5 class="leading-tight">You have successfully saved a JOI as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/job_issue/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
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
									<button type="button" class="btn btn-danger btn-sm !rounded-full w-full" @click="deleteJOTerms(terms_id,'yes')" >Yes</button>
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
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelAllJO('yes')">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>