<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	import moment from 'moment'
	const preview =  ref();
	const suppliers =  ref([]);
	const dangerAlert = ref(false)
	const successAlert = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
	const vendor =  ref([]);
	const vendor_details_id =  ref('');
	const pr_no =  ref('');
	const po_no =  ref('');
	const prepared_by =  ref('');
	const prno_dropdown =  ref([]);
	const po_head =  ref([]);
	const pr_head =  ref([]);
	const po_details =  ref([]);
	const rfq_terms =  ref([]);
	const vat =  ref('');
	const vat_percent =  ref(12);
	const vat_amount =  ref(0);
	const vat_in_ex =  ref(0);
	const shipping_cost =  ref(0);
	const handling_fee =  ref(0);
	const discount =  ref(0);
	const orig_amount =  ref(0);
	const grand_total =  ref(0);
	const new_data =  ref(0);
	const balance =  ref(0);
	let signatories=ref([]);
	const checked_by =  ref(0);
	const recommended_by =  ref(0);
	const approved_by =  ref(0);
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		getSupplier()
		getSignatories()
	})
	const getSupplier = async () => {
		let response = await axios.get("/api/supplier_dropdown");
		suppliers.value = response.data.suppliers;
	}

	const getSupplierPR = async () => {
		let response = await axios.get("/api/get_prno/"+vendor_details_id.value);
		prno_dropdown.value = response.data.prno_dropdown;
		po_head.value=[]
	}

	const generatePO = async () => {
		let response = await axios.get("/api/generate_po/"+vendor_details_id.value+'/'+pr_no.value);
		po_no.value = response.data.po_no;
		po_head.value = response.data.po_head;
		pr_head.value = response.data.pr_head;
		po_details.value = response.data.po_details;
		rfq_terms.value = response.data.rfq_terms;
		vendor.value = response.data.vendor;
		grand_total.value = response.data.grand_total;
		prepared_by.value = response.data.prepared_by;
	}

	const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
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
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
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

	
	const pr_det = ref(false)

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
	const formatNumber = (number) => {
      return number.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
	const additionalCost = () =>{
		var total = (orig_amount.value==0) ? grand_total.value : orig_amount.value;
		var discount_display= (discount.value!='') ? discount.value : 0;
		var vat_percent = document.getElementById("vat_percent").value;
		var percent=vat_percent/100;
		var new_vat= (parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) * percent;
		var new_total = (parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value) + new_vat) - parseFloat(discount_display);
		document.getElementById("grand_total").innerHTML  = formatNumber(new_total)
		new_data.value=parseFloat(new_total)
		document.getElementById("vat_amount").value=formatNumber(new_vat);
	}

	const vatChange = () => {
		var total = (orig_amount.value==0) ? grand_total.value : orig_amount.value;
		var discount_display= (discount.value!='') ? discount.value : 0;
		var vat_percent = document.getElementById("vat_percent").value;
        var percent=vat_percent/100;
        var new_vat = (parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) * parseFloat(percent);
        document.getElementById("vat_amount").value = formatNumber(new_vat);
        var new_total=(parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value) + parseFloat(new_vat)) - parseFloat(discount_display);
        document.getElementById("grand_total").innerHTML=formatNumber(new_total);
		new_data.value=parseFloat(new_total)
	} 

	const selectVat = () => {
		if(vat.value==1){
			var vat_percent = document.getElementById("vat_percent").value;
			var percent=vat_percent/100;
			vat_amount.value=new_data.value * percent;
			additionalCost()
		}else{
			vat_amount.value=0
			additionalCost()
		}
	}

	const checkBalance = async (pr_details_id,qty,count) => {
		var grandtotal=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			grandtotal += parseFloat(p);
        });
		var vat = document.getElementById("vat_percent").value;
        var percent=vat/100;
		var new_vat = (parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) * parseFloat(percent);
		vat_amount.value=new_vat;
		grand_total.value=formatNumber(grandtotal + new_vat);
		orig_amount.value=formatNumber(grandtotal);
		let response = await axios.get("/api/check_balance/"+pr_details_id);
		balance.value = response.data.balance;
		if(qty>balance.value.pr_qty){
			document.getElementById('balance_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = true;
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_checker'+count).style.backgroundColor = '#FFFFFF';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = false;
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}

	const onSave = () => {
		const formData= new FormData()
		// alert(document.querySelector("#grand_total").textContent);
		formData.append('shipping_cost', shipping_cost.value)
		formData.append('handling_fee', handling_fee.value)
		formData.append('discount', discount.value)
		formData.append('vat', vat.value)
		formData.append('vat_percent', vat_percent.value)
		formData.append('vat_amount', vat_amount.value)
		formData.append('checked_by', checked_by.value)
		formData.append('approved_by', approved_by.value)
		formData.append('recommended_by', recommended_by.value)
		formData.append('terms_list', JSON.stringify(terms_list.value))
		formData.append('rfq_terms', JSON.stringify(rfq_terms.value))
		formData.append('other_list', JSON.stringify(other_list.value))
		formData.append('po_details', JSON.stringify(po_details.value))
		if(props.id==0){
			formData.append('approved_date', approved_date.value)
			formData.append('remarks', remarks.value)
			formData.append('prepared_by', prepared_by.value)
			formData.append('recommended_by', recommended_by.value)
			formData.append('approved_by', approved_by.value)
		}else{
			formData.append('petty_cash_id', petty_cash.value.id)
			formData.append('approved_date', petty_cash.value.approved_date)
			formData.append('remarks', petty_cash.value.remarks)
			formData.append('prepared_by', petty_cash.value.prepared_by)
			formData.append('recommended_by', petty_cash.value.recommended_by)
			formData.append('approved_by', petty_cash.value.approved_by)
		}
		formData.append('props_id', props.id)
		if(prdetails.value.length!=0 || item_list.value.length!=0){
			axios.post(`/api/save_upload/${pr_head_id.value}`,formData).then(function (response) {
				if(prhead.value.petty_cash==0){
					success.value='You have successfully saved new pr.'
					successAlert.value=!successAlert.value
				}else{
					success.value='You have successfully saved new pr.'
					successAlert.value=!successAlert.value
					router.push('/pur_req/view/'+prhead.value.id)
				}
				item_list.value=[]
				getImportdata(pr_head_id.value)
			}, function (err) {
				// error.value = err.response.data.message;
				error.value=''
				error_pr.value=[]
				if (err.response.data.errors.pr_no) {
					error_pr.value.push(err.response.data.errors.pr_no[0])
				}
				if (err.response.data.errors.department_id) {
					error_pr.value.push(err.response.data.errors.department_id[0])
				}
				if (err.response.data.errors.location) {
					error_pr.value.push(err.response.data.errors.location[0])
				}
				if (err.response.data.errors.date_prepared) {
					error_pr.value.push(err.response.data.errors.date_prepared[0])
				}
				if (err.response.data.errors.requestor) {
					error_pr.value.push(err.response.data.errors.requestor[0])
				}
				if (err.response.data.errors.enduse) {
					error_pr.value.push(err.response.data.errors.enduse[0])
				}
				if (err.response.data.errors.purpose) {
					error_pr.value.push(err.response.data.errors.purpose[0])
				}	
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}else{
			error.value = 'Items cannot be empty, Please fill in data.';
			dangerAlerterrors.value=!dangerAlerterrors.value
		}
    }
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Purchase Order <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_po">Purchase Order</a></li>
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
									<label class="text-gray-500 m-0" for="">Choose Supplier and PR No</label>
									<input type="file" name="img[]" class="file-upload-default">
									<div class="input-group col-xs-12">
										<select class="form-control file-upload-info" @change="getSupplierPR()" v-model="vendor_details_id">
											<option value="">--Select Supplier--</option>
											<option :value="sup.id" v-for="sup in suppliers" :key="sup.id">{{ sup.vendor_name }} ({{ sup.identifier }})</option>
										</select>
										<select class="form-control file-upload-info" v-model="pr_no">
											<option value="">--Select PR Number--</option>
											<option :value="p.pr_no+'+'+p.id" v-for="p in prno_dropdown" :key="p.pr_no+'+'+p.id">{{ p.pr_no }} ({{ p.aoq_date }} - {{ p.id }})</option>
										</select>
										<span class="input-group-append">
											<button class="btn btn-primary" type="button" @click="generatePO()">Select</button>
											<!-- <button class="btn btn-primary" type="button" @click="pr_det = !pr_det">Select</button> -->
										</span>
									</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div class="pt-1">
							<!-- <div v-show="pr_det"> -->
							<div v-if="po_head && po_head.length!=0">
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
										<span class="text-sm text-gray-700">{{ po_no }}</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
										<span class="text-sm text-gray-700">{{ moment().format('MMM. DD,YYYY') }}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">Supplier: </span>
										<span class="text-sm text-gray-700">{{vendor.vendor_name}} ({{ vendor.identifier }})</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">Address:</span>
										<span class="text-sm text-gray-700">{{vendor.address}}</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">Telephone: </span>
										<span class="text-sm text-gray-700">{{vendor.phone}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">Contact Person: </span>
										<span class="text-sm text-gray-700">{{vendor.contact_person}}</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">Telefax: </span>
										<span class="text-sm text-gray-700">{{vendor.fax}}</span>
									</div>
								</div>
								<div class="" >
									<br>
									<div class="row">
										<div class="col-lg-12">
											<div class="border-2">
												<table class="table-bordered w-full !text-xs">
													<tr class="bg-gray-100">
														<td class="uppercase p-1 text-center" width="3%">#</td>
														<td class="uppercase p-1 text-center" width="7%">Qty</td>
														<td class="uppercase p-1 text-center" width="7%">Unit</td>
														<td class="uppercase p-1" colspan="2">Description</td>
														<td class="uppercase p-1 text-center" width="12%">Unit Price</td>
														<td class="uppercase p-1 text-center" width="12%">Total</td>
													</tr>
													<tr class="" v-for="(pd, index) in po_details">
														<span hidden>{{ totalprice=formatNumber(pd.unit_price * pd.remaining_qty) }}</span>
														<td class="border-y-none p-1 text-center">{{ index+1}}</td>
														<td class="border-y-none p-0 text-center">
															<input type="number" min="0" @keyup="checkBalance(pd.pr_details_id,pd.remaining_qty, index)" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 border-b p-1 text-center" :id="'balance_checker'+index" v-model="pd.remaining_qty">
														</td>
														<td class="border-y-none p-1 text-center">{{ pd.uom }}</td>
														<td class="border-y-none p-1" colspan="2">{{ pd.offer }}</td>
														<td class="border-y-none p-1 text-right">{{pd.unit_price}} {{ pd.currency }}</td>
														<td class="border-y-none p-1 text-right"> <input type="text" class="text-center tprice" :id="'tprice'+index" v-model="totalprice" readonly></td>
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
														<td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="5">
															<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>{{pr_head.pr_no}}</p>
															<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>{{pr_head.requestor}}</p>
															<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>{{pr_head.enduse}}</p>
															<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>{{pr_head.purpose}}</p>
														</td>
														<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
														<td class="p-0"><input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-0.5 text-right pr-1" v-model="shipping_cost" @keyup="additionalCost()"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
														<td class="p-0"><input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" v-model="handling_fee" @keyup="additionalCost()"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
														<td class="p-0"><input  type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" v-model="discount" @keyup="additionalCost()"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-0 text-right" colspan="2">
															<div class="flex justify-end">
																<!-- <span class="p-1" >VAT</span> -->
																<select name="" class="border px-1 text-xs" id="" @change="selectVat()" v-model="vat">
																	<option value="">--Select--</option>
																	<option value="1">VAT</option>
																	<option value="2">NON-VAT</option>
																</select>
															</div>
														</td>
														<!-- Kamo na bahala mag hide sang duwa ka input sa dalom kung Non VAT-->
														<!-- VAT -->
														<td class="p-0" v-if="vat==1">
															<div class="flex p-0">
																<input type="number" min="0" class="w-10 bg-yellow-50 border-r text-center" v-model="vat_percent" id="vat_percent" @keyup="vatChange()">%
                                                                <input type="text" class="w-10 bg-yellow-50 border-r text-center" value="12" hidden>
                                                                <input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" id="vat_amount" v-model="vat_amount" @keyup="additionalCost()">
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
												<tr v-for="(rt,indexterms) in rfq_terms">
													<td class="align-top text-center" width="4%">{{indexterms + 4}}.</td>
													<td class="align-top" colspan="2">
														<div class="flex justify-between">
															<!-- <span class="w-32">{{ rt.terms }} </span> -->
															<textarea class="w-full bg-yellow-50 px-1" id="" v-model="rt.terms"></textarea>
														</div>
													</td>
												</tr>
												<tr v-for="(t,index) in terms_list">
													<td class="align-top text-center" width="4%">{{ index + 4 +rfq_terms.length }}.</td>
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
													<td class="px-1" colspan="2">{{ o.other_ins }}</td>
													<td class="p-0 align-top" width="1">
														<button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1">
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
													<td class="text-center p-1">{{prepared_by}}</td>
													<td></td>
													<td class="text-center p-1">
														<select class="text-center" v-model="checked_by">
															<option value='0'>--Select Reviewed/Checked by--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
													</td>
													<td></td>
													<td class="text-center p-1">
														<select class="text-center" v-model="recommended_by">
															<option value='0'>--Select Recommended by--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
													</td>
													<td></td>
													<td class="text-center p-1">
														<select class="text-center" v-model="approved_by">
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
												<button @click="openWarningAlert()" class="btn btn-warning w-26 !text-white" id="draft">Save as Draft</button>
												<button @click="openSuccessAlert()" type="submit" class="btn btn-primary w-36" id="save">Save</button>
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
									<h5 class="leading-tight">You have successfully created a new PO.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/pur_po/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a>
									<a href="/pur_po/view" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a>
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
									<h5 class="leading-tight">You have successfully saved a PO as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/pur_po/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>