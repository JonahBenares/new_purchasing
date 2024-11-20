<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon, ExclamationTriangleIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	import moment from 'moment'
	const vendor =  ref();
	const buttons_set =  ref()
	const approval_set =  ref(false)
	const error =  ref('');
	const success =  ref('');
	const dangerAlert = ref(false)
	const successAlert = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
    const approveAlert = ref(false)
	const hide_button = ref()
	const hideAlert = ref(true)
	const orig_amount =  ref(0);
	const balance =  ref(0);
	const balance_overall =  ref(0);
	const remaining_balance =  ref([]);
	const prepared_by =  ref('');
	const checked_by =  ref('');
	const recommended_by =  ref('');
	const approved_by =  ref('');
	const approved_by_rev =  ref(0);
	const approved_date =  ref('');
	const approved_reason =  ref('');
	let signatories=ref([]);
	const grand_total =  ref(0);
	const grand_total_old =  ref(0);
	const newvat =  ref(0);
	const vat =  ref(0);
	const vat_amount =  ref(0);
	const vat_in_ex =  ref(0);
	const discount_labor =  ref(0);
	const discount_material = ref(0)
	const grand_labor_total = ref(0)
	const grand_material_total = ref(0)
	const overall_total = ref(0)
	const new_data =  ref(0);
	const instruction_id =  ref(0);
	const terms_id =  ref(0);
	const joi_head_rev = ref([])
	const joi_head = ref([])
	const joi_dr_rev = ref([])
	const joi_dr = ref([])
	const joi_dr_labor = ref([])
	const joi_dr_material = ref([])
	const jor_head = ref([])
	const joi_vendor = ref([])
	const joi_labor_details = ref([])
	const joi_material_details = ref([])
	const joi_terms = ref([])
	const joi_instructions = ref([])
	const remaining_labor_balance=ref([])
	const remaining_material_balance=ref([])
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		joRevise()
		getSignatories()
	})
	const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
	}
	const formatNumber = (number) => {
      return number.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
	const joRevise= async () => {
		let response = await axios.get("/api/jo_viewdetails/"+props.id);
		joi_head_rev.value = response.data.joi_head_array;
		joi_head.value = response.data.joi_head;
		joi_dr_rev.value = response.data.joi_dr_array;
		joi_dr.value = response.data.joi_dr;
		joi_dr_labor.value = response.data.joi_dr_labor;
		joi_dr_material.value = response.data.joi_dr_material;
		discount_labor.value = response.data.joi_head.discount;
		discount_material.value = response.data.joi_head.discount_material;
		vat.value = response.data.joi_head.vat;
		vat_amount.value = response.data.joi_head.vat_amount;
		vat_in_ex.value = response.data.joi_head.vat_in_ex;
		newvat.value= (response.data.grand_labor_total + response.data.grand_material_total) * (response.data.joi_head.vat/100)
		grand_labor_total.value = response.data.grand_labor_total;
		grand_material_total.value = response.data.grand_material_total;
		overall_total.value=(response.data.grand_labor_total + response.data.grand_material_total + newvat.value) - (discount_labor.value + discount_material.value)
		jor_head.value = response.data.jor_head;
		joi_vendor.value = response.data.joi_vendor;
		joi_labor_details.value = response.data.joi_details_view;
		joi_material_details.value = response.data.joi_material_details_view;
		joi_terms.value = response.data.joi_terms;
		joi_instructions.value = response.data.joi_instructions;
		prepared_by.value = response.data.prepared_by;
		checked_by.value = response.data.checked_by;
		recommended_by.value = response.data.recommended_by;
		approved_by.value = response.data.approved_by;
		var cancelled_qty=0;
		response.data.joi_labor_details.forEach(function (val, index, theArray) {
			cancelled_qty +=(val.status=='Cancelled') ? val.total_cost : ''
			grand_total_old.value = (((response.data.grand_labor_total + response.data.grand_material_total)-cancelled_qty) + newvat.value) - (response.data.joi_head.discount + response.data.joi_head.discount_material)
			grand_total.value = (((response.data.grand_labor_total + response.data.grand_material_total)-cancelled_qty) + newvat.value) - (response.data.joi_head.discount + response.data.joi_head.discount_material)
		});
		joi_labor_details.value.forEach(function (val, index, theArray) {
			checkLaborRemainingQty(val.jor_labor_details_id,val.jo_rfq_labor_offer_id,val.quantity,index)
		});
		joi_material_details.value.forEach(function (vals, index, theArray) {
			checkMaterialRemainingQty(vals.jor_material_details_id,vals.jo_rfq_material_offer_id,vals.quantity,index)
		});
		if(joi_head.value.status=='Revised'){
			joReviseTemp()
			infoAlert.value = !hideAlert.value
			approval_set.value = !approval_set.value
			buttons_set.value = !hide_button.value
		}
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
        var new_vat = (parseFloat(total) + parseFloat(totalm)) * parseFloat(percent);
        document.getElementById("vat_amount").value = new_vat.toFixed(2);
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
		var new_vat = (parseFloat(grandlabortotal) + parseFloat(grandmaterialtotal)) * parseFloat(percent);
		vat_amount.value=new_vat;
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
		if(qty>all_qty){
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
		var new_vat = (parseFloat(grandlabortotal) + parseFloat(grandmaterialtotal)) * parseFloat(percent);
		vat_amount.value=new_vat;
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
		if(qty>all_qty){
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

	const joReviseTemp= async () => {
		let response = await axios.get("/api/jo_viewdetails/"+props.id);
		joi_head.value = response.data.joi_head;
		joi_head_temp.value = response.data.joi_head_temp;
		joi_labor_details_temp.value = response.data.joi_labor_details_temp;
		joi_material_details_temp.value = response.data.joi_material_details_temp;
		joi_terms_temp.value = response.data.joi_terms_temp;
		joi_instructions_temp.value = response.data.joi_instructions_temp;
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
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		warningAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
		approveAlert.value = !hideAlert.value
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
	const save_button = ref();

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
				other_ins:other_text.value,
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
	const printDiv = () => {
		window.print();
	}

	const deleteTerms = (id,option) => {
		if(option=='yes'){
			axios.get(`/api/delete_terms/`+id).then(function () {
				dangerAlert_terms.value = !hideAlert.value
				success.value='Successfully deleted term!'
				successAlertCD.value = !successAlertCD.value
				poRevise()
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
			axios.get(`/api/delete_instructions/`+id).then(function () {
				dangerAlert_instructions.value = !hideAlert.value
				success.value='Successfully deleted instruction!'
				successAlertCD.value = !successAlertCD.value
				poRevise()
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

	const onSave = () => {
		const formData= new FormData()
		var total = document.querySelector("#grand_total").textContent;
		var total_replace = total.replace(",", "");
		formData.append('po_head', JSON.stringify(po_head_rev.value))
		formData.append('shipping_cost', shipping_cost.value)
		formData.append('handling_fee', handling_fee.value)
		formData.append('discount', discount_labor.value)
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
		axios.post(`/api/save_change_po`,formData).then(function (response) {
			infoAlert.value = !hideAlert.value
			approval_set.value = !approval_set.value
			buttons_set.value = !hide_button.value
			success.value='You have successfully revise po, please fill in approve form below.'
			successAlertCD.value=!successAlertCD.value
			const btn_save = document.getElementById("confirm_alert");
			btn_save.disabled = true;
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
		formData.append('discount', discount_labor.value)
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
			axios.post(`/api/save_approved_revision`,formData).then(function (response) {
				success.value='You have successfully revised po'
				successAlertCD.value=!successAlertCD.value
				const btn_save = document.getElementById("save_approve");
				btn_save.disabled = true;
				setTimeout(() => {
					router.push('/pur_po/view/'+props.id)
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
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Issuance <small>Revise</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_issue">JO Issuance</a></li>
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
						<span class="font-bold uppercase text-lg text-center text-yellow-500">CHANGE ORDER FORM</span>
						<hr class="border-dashed mt-">
						<div>
							<div class="row">
								<div class="col-lg-1 col-sm-1 col-md-1">
									<span class="text-sm">TO:</span>
								</div>
								<div class="col-lg-11 col-sm-11 col-md-11">
									<p class="m-0 font-bold capitalize">{{ joi_vendor.vendor_name }} ({{ joi_vendor.identifier }})</p>
										<p class="m-0">{{joi_vendor.contact_person}}</p>
										<p class="m-0">{{joi_vendor.address}}</p>
										<p class="m-0">{{joi_vendor.phone}}</p>
								</div>
							</div>
							<hr class="border-dashed">
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Needed: </span>
										<input type="text" class="border-b bg-white w-full" disabled :value="moment(joi_head.date_needed).format('MMM. DD,YYYY')">
									</div>
								</div>
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-52">Completion of Work: </span>
										<input type="text" class="border-b bg-white w-full" disabled :value="moment(joi_head.completion_work).format('MMM. DD,YYYY')">
									</div>	
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Prepared: </span>
										<input type="text" class="border-b bg-white w-full" disabled :value="moment(joi_head.date_prepared).format('MMM. DD,YYYY')">
									</div>
								</div>
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-52">CENPRI JOR No: </span>
										<input type="text" class="border-b bg-white w-full" disabled v-model="jor_head.site_jor">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Start of Work: </span>
										<input type="text" class="border-b bg-white w-full" disabled :value="moment(joi_head.start_of_work).format('MMM. DD,YYYY')">
									</div>
								</div>
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-52">JO No: </span>
										<input type="text" class="border-b bg-white w-full" disabled :value="joi_head.joi_no">
									</div>
								</div>
							</div>
							
							<div class="" >
								<br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="font-bold uppercase text-sm text-gray-500">Current Data</span>
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
													<td colspan="6"><span class="font-bold">{{ jor_head.general_description}} </span></td>
												</tr>
												<tr class="" v-for="(jld,index) in joi_labor_details" v-if="joi_head.status!='Revised'">
													<span hidden>{{ totalprice=formatNumber(jld.unit_price * jld.quantity) }}</span>
													<td class="border-y-none p-1" colspan="3">
														{{ jld.item_description}}
													</td>
													<td class="border-y-none p-1 text-center">
														<input type="text" min="0" @keyup="checkLaborBalance(jld.jor_labor_details_id,jld.jo_rfq_labor_offer_id,vat,remaining_labor_balance[index], index)" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 border-b p-1 text-center" :id="'balance_labor_checker'+index" v-model="remaining_labor_balance[index]">
													</td>
													<td class="border-y-none p-1 text-center">{{jld.uom}}</td>
													<td class="border-y-none p-1 text-right">{{jld.unit_price}} {{ jld.currency }}</td>
													<td class="border-y-none p-1 text-right"><input type="text" class="text-center tprice" :id="'tprice'+index" v-model="totalprice" readonly></td>
												</tr>
												<tr class="" v-for="(jld,index) in joi_labor_details_temp" v-else>
													<span hidden>{{ totalprice=formatNumber(jld.unit_price * jld.quantity) }}</span>
													<td class="border-y-none p-1" colspan="3">
														{{ jld.item_description}}
													</td>
													<td class="border-y-none p-1 text-center">
														<input type="text" min="0" @keyup="checkLaborBalance(jld.jor_labor_details_id,jld.jo_rfq_labor_offer_id,vat,remaining_labor_balance[index], index)" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 border-b p-1 text-center" :id="'balance_labor_checker'+index" v-model="remaining_labor_balance[index]">
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
												<tr class="" v-for="(jmd,indexes) in joi_material_details" v-if="joi_head.status!='Revised'">
													<span hidden>{{ totalmprice=formatNumber(jmd.unit_price * remaining_material_balance[indexes]) }}</span>
													<td class="border-y-none p-1 text-center">{{indexes+1}}</td>
													<td class="border-y-none p-1" colspan="2">{{jmd.item_description}}</td>
													<td class="border-y-none p-1 text-center">
														<input type="text" min="0" @keyup="checkMaterialBalance(jmd.jor_material_details_id,jmd.jo_rfq_material_offer_id,vat,remaining_material_balance[indexes], indexes)" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 border-b p-1 text-center" :id="'balance_material_checker'+indexes" v-model="remaining_material_balance[indexes]">
													</td>
													<td class="border-y-none p-1 text-center">{{jmd.uom}}</td>
													<td class="border-y-none p-1 text-right">{{jmd.unit_price}} {{ jmd.currency }}</td>
													<td class="border-y-none p-1 text-right"><input type="text" class="text-center tmprice" :id="'tmprice'+indexes" v-model="totalmprice" readonly></td>
												</tr>
												<tr class="" v-for="(jmd,indexes) in joi_material_details_temp" v-else>
													<span hidden>{{ totalmprice=formatNumber(jmd.unit_price * remaining_material_balance[indexes]) }}</span>
													<td class="border-y-none p-1 text-center">{{indexes+1}}</td>
													<td class="border-y-none p-1" colspan="2">{{jmd.item_description}}</td>
													<td class="border-y-none p-1 text-center">
														<input type="text" min="0" @keyup="checkMaterialBalance(jmd.jor_material_details_id,jmd.jo_rfq_material_offer_id,vat,remaining_material_balance[indexes], indexes)" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 border-b p-1 text-center" :id="'balance_material_checker'+indexes" v-model="remaining_material_balance[indexes]">
													</td>
													<td class="border-y-none p-1 text-center">{{jmd.uom}}</td>
													<td class="border-y-none p-1 text-right">{{jmd.unit_price}} {{ jmd.currency }}</td>
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
													<td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="6"></td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Total Labor</td>
													<td class="p-0"><input disabled type="text" class="w-full bg-white p-0.5 text-right pr-1" :value="grand_labor_total"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Total Materials</td>
													<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" :value="grand_material_total"></td>
												</tr>
												
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Labor</td>
													<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" :value="discount_labor"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Material</td>
													<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" :value="discount_material"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
													<td class="p-0">
														<div class="flex">
															<input disabled type="text" class="w-10 bg-white border-r text-center" placeholder="%" :value="vat">
															<input disabled type="text" class="w-full bg-white p-1 text-right" :value="vat_amount">
														</div>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">{{overall_total}}</td>
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
											<table class="table-bordered w-full !text-xs">
												<tr class="!border-b-3 bg-yellow-50">
													<td colspan="7" class="py-2">
														<textarea class="text-sm font-bold text-gray-600 bg-yellow-50  text-center m-0 w-full resize" rows="1">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</textarea>
														<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
													</td>
												</tr>
												<tr class="bg-yellow-100">
													<td class="uppercase p-1" colspan="3">Scope of Work</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="10%">Total</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1" colspan="3">
														<textarea class="font-bold w-full resize" rows="1">Supply of manpower/labor, laboratory tools/equipment, and technical expertise for the following:</textarea>
														<textarea name="" id="" class="w-full resize" rows="10">1. 1. Standard governor overhauling/dismantling, cleaning and replacement of parts as seen necessary (i.e. gaskets, bearings, o-rings, etc.)2. Inspection and checking of all parts for wear, cracks, corrosion and other damages.3. Repair and replacement of parts as seen upon inspection.4. Setting of internal parts and mounting of the governor.5. Calibration and bench testing for:5.1. Speed Setting and Indicator5.2. Speed Droop Setting and Indicator5.3. Load Limit Setting and Indicator6. Functional test of shut-down solenoid valve7. Testing and Commissioning8. Submission of inspection, service, commissioning and bench testing reports.9. Other works necessary for job completion.
														</textarea>
													</td>
													<td class="border-y-none p-1 text-center"><input type="text" value="5" class="w-full text-center"></td>
													<td class="border-y-none p-1 text-center"><input type="text" value="lot" class="w-full text-center"></td>
													<td class="border-y-none p-1 text-right"><input type="text" value="100.00" class="w-full text-right"></td>
													<td class="border-y-none p-1 text-right"><input type="text" value="500.00" class="w-full text-right"></td>
												</tr>
												<tr class="bg-yellow-100">
													<td class="p-1 text-center" width="3%">#</td>
													<td class="p-1" colspan="2">Materials:</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="10%">Total</td>
												</tr>
												<tr class="">
													<td class="p-1 text-center align-top">1</td>
													<td class="p-0 align-top " colspan="2">
														<textarea name="" id="" class="w-full  p-1 resize" rows="1">Monitor</textarea>
													</td>
													<td class="align-top text-center"><input type="text" class="w-full text-center p-1" value="5"></td>
													<td class="align-top text-center"><input type="text" class="w-full text-center p-1" value="lot"></td>
													<td class="align-top text-right"><input type="text" class="w-full text-right p-1" value="100.00"></td>
													<td class="align-top text-right"><input type="text" class="w-full text-right p-1" value="500.00"></td>
												</tr>
												<tr class="">
													<td class="p-1 text-center align-top">1</td>
													<td class="p-0 align-top " colspan="2">
														<textarea name="" id="" class="w-full  p-1 resize" rows="1">Mouse</textarea>
													</td>
													<td class="align-top text-center"><input type="text" class="w-full text-center p-1" value="5"></td>
													<td class="align-top text-center"><input type="text" class="w-full text-center p-1" value="lot"></td>
													<td class="align-top text-right"><input type="text" class="w-full text-right p-1" value="100.00"></td>
													<td class="align-top text-right"><input type="text" class="w-full text-right p-1" value="500.00"></td>
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
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">JOR Number:</span>PR-19772-8727</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
													</td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Total Labor</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-0.5 text-right pr-1" value="200.00"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Total Materials</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right" value="200.00"></td>
												</tr>
												
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Labor</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right" value="200.00"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Material</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right" value="100.00"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
													<td class="p-0">
														<div class="flex">
															<input type="text" class="w-10 bg-yellow-50 border-r text-center" placeholder="%" value="">
															<input type="text" class="w-full bg-yellow-50 p-1 text-right" value="">
														</div>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">1000.00</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								
								<div class="row mt-2">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<table class="table-bordered !text-xs w-full">
											<tr>
												<td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
											</tr>
											<tr class="">
												<td class="p-0" colspan="2">
													<input type="text" class="p-1 w-full bg-yellow-50" v-model="terms_text" id="check_terms">
												</td>
												<td class="p-0" width="1">
													<button type="button" @click="addRowTerms" class="btn btn-primary p-1">
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
														<select name="" class="w-full bg-yellow-50" id="">
															<option value="">Inclusive of VAT</option>
															<option value="">Exclusive of VAT</option>
														</select>
													</div>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">4.</td>
												<td class="align-top  pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-32">Item Warranty </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">5.</td>
												<td class="align-top  pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-32">Delivery Term </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr v-for="(t,index) in terms_list">
												<td class="align-top text-center" width="4%">{{ index + 6 }}.</td>
												<td class="px-1" colspan="2">
													<span class="w-32">{{ t.terms_condition }}</span>
												</td>
												<td class="p-0 align-top" width="1">
													<button type="button" class="btn btn-danger p-1">
														<XMarkIcon fill="none" @click="removeTerms(index)" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
										</table>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<table class="table-bordered !text-xs w-full">
											<tr>
												<td class="p-1 uppercase" colspan="3">Other Instructions</td>
											</tr>
											<tr class="">
												<td class="p-0" colspan="2">
													<input type="text" class="p-1 w-full bg-yellow-50" v-model="other_text" id="check_others">
												</td>
												<td class="p-0" width="1">
													<button type="button" @click="addRowOther" class="btn btn-primary p-1" >
														<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
													</button>
												</td>
											</tr>
											<tr v-for="(o, indexes) in other_list">
												<td class="px-1" colspan="2">{{ o.other_ins }}</td>
												<td class="p-0 align-top" width="1">
													<button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
											<tr>
												<td colspan="2" class="p-1">Sample Notes</td>
												<td class="p-0 align-top" width="1">
													<button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
										</table>
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
														<span>PHP</span>
														<span>18,999.99</span>
													</div>
												</td>
												<td width="14%"></td>
												<td width="8%" class="font-bold text-sm text-gray-500">Conforme:</td>
												<td  width="30%" class="border-b border-gray-400 px-4"><input type="text" class="w-full text-center text-sm capitalize"></td>
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
												<td class="text-center p-0"><input type="text" class="text-center bg-yellow-50 p-1 w-full" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-0"><input type="text" class="text-center bg-yellow-50 p-1 w-full" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-0"><input type="text" class="text-center bg-yellow-50 p-1 w-full" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-0"><input type="text" class="text-center bg-yellow-50 p-1 w-full" placeholder="Employee Name"></td>
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
								<hr	class="border-dashed mt-4">
								<div class="row mt-2 po_buttons">
									<div class="col-lg-12">
										<span class="text-xs">Internal Comment</span>
										<textarea name="" id=""  rows="2" class="w-full bg-yellow-50 text-xs border p-1"></textarea>
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
												<input type="date" class="form-control">
											</div>
											<div class="col-lg-3 col-md-3">
												<span class="text-sm p-1">Approve By</span>
												<select class="form-control">
													<option value="">Beverly Espareal</option>
												</select>
											</div>
											<div class="col-lg-6 col-md-6">
												<span class="text-sm p-1">Reason</span>
												<textarea name="" class="form-control" rows="1"></textarea>
											</div>
											<div class="col-lg-1 col-md-1">
												<span class="text-sm p-1"><br></span>
												<button @click="openApproveAlert()" class="btn btn-primary btn-sm" >Approve</button>
											</div>
										</div>
									</div>
									<div class="" :class="{ hidden:buttons_set }">
										<div class="row my-2" > 
											<div class="col-lg-12 col-md-12">
												<div class="flex justify-center space-x-2">
													<div class="flex justify-between space-x-1">
														<button type="submit" class="btn btn-warning w-26 !text-white" @click="openWarningAlert()">Save as Draft</button>
														<button  class="btn btn-primary w-36"  @click="openInfoAlert()">Save</button>
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
									<h5 class="leading-tight">Are you sure you want to revise this JOI?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/job_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<button @click="closeInfoAlert2()" class="btn !bg-blue-500 !text-white btn-sm !rounded-full w-full">Save</button>
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
									<!-- <a href="/job_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">No</button>
									<a href="/job_issue/view" class="btn !bg-blue-500 !text-white btn-sm !rounded-full w-full">Yes</a>
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
									<!-- <a href="/job_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<!-- <a href="/job_issue/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a> -->
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>