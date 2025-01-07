<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, MagnifyingGlassIcon, CheckIcon} from '@heroicons/vue/24/solid'
   	import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	import moment from 'moment'
	const router = useRouter();
	const prno_dropdown =  ref([]);
	const suppliers =  ref([]);
	const po_details =  ref([]);
	const pr_head =  ref([]);
	const po_head =  ref([]);
	const vendor =  ref([]);
	let currency=ref([]);
	let signatories=ref([]);
	const vendor_terms =  ref([]);
	const pr_no =  ref('');
	const po_no =  ref('');
	const dr_no =  ref('');
	const prepared_by =  ref('');
	const vendor_details_id =  ref('');
	const checked_by =  ref(0);
	const recommended_by =  ref(0);
	const approved_by =  ref(0);
	const vat =  ref(0);
	const total =  ref(0);
	const balance =  ref(0);
	const grand_total =  ref(0);
	const new_data =  ref(0);
	const vat_percent =  ref(12);
	const vat_amount =  ref(0);
	const vat_in_ex =  ref(0);
	const shipping_cost =  ref(0);
	const handling_fee =  ref(0);
	const discount =  ref(0);
	let vendor_list=ref([]);
	let vendor_name=ref('');
	let terms_list=ref([]);
	let terms_text=ref("");
	let other_list=ref([]);
	let other_text=ref("");
	let newvat=ref(0);
	const totals=ref(0);
	const instruction_id=ref(0);
	const terms_id=ref(0);
	let pohead_id=ref(0);
	const formatter = new Intl.NumberFormat('en-US', { 
        minimumFractionDigits: 4 
    });
	const cancel_all_reason=ref('');

	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})

	onMounted(async () => {
		getDirectPR()
		getSignatories()
		if(props.id!=0){
			dpoDraft()
		}
	})

	const getDirectPR = async () => {
		let response = await axios.get("/api/get_direct_pr/");
		prno_dropdown.value = response.data.prno_dropdown;
		// suppliers.value=[]
	}

	const getSupplierDPR = async () => {
		if(pr_no.value != ''){
			let response = await axios.get("/api/direct_supplier_dropdown/");
			suppliers.value = response.data.suppliers;
			document.getElementById("dpr_supplier").disabled = false;
		}else{
			document.getElementById("dpr_supplier").disabled = true;
		}
	}

	const OpenBtn = async () => {
		if(vendor_details_id.value != ''){
			document.getElementById("generatepobtn").disabled = false;
		}else{
			document.getElementById("generatepobtn").disabled = true;
		}
	}

	const generatePO = async () => {
		formatter.value = new Intl.NumberFormat('en-US', {
			minimumFractionDigits: 4,      
		})
			document.getElementById("generatepobtn").disabled = false;
			let response = await axios.get("/api/generate_dpo/"+pr_no.value+'/'+vendor_details_id.value);
			dr_no.value = response.data.dr_no;
			po_no.value = response.data.po_no;
			po_details.value = response.data.po_details;
			po_head.value = response.data.po_head;
			vendor_terms.value = response.data.vendor_terms;
			vendor.value = response.data.vendor;
			currency.value = response.data.currency
			pr_head.value = response.data.pr_head;
			prepared_by.value = response.data.prepared_by;
			formatter.value = new Intl.NumberFormat('en-US', {
				minimumFractionDigits: 4,      
			})
	}

	const dpoDraftDisplay = async () => {
		formatter.value = new Intl.NumberFormat('en-US', {
			minimumFractionDigits: 4,      
		})
		let response = await axios.get("/api/dpo_viewdetails/"+pohead_id.value);
		vendor_terms.value = response.data.po_terms;
		other_list.value = response.data.po_instructions;
	}

	const dpoDraft = async () => {
		formatter.value = new Intl.NumberFormat('en-US', {
			minimumFractionDigits: 4,      
		})
		let response = await axios.get("/api/dpo_viewdetails/"+props.id);
		pohead_id.value=props.id
		prepared_by.value = response.data.prepared_by;
		checked_by.value = response.data.checked_by;
		recommended_by.value = response.data.recommended_by;
		approved_by.value = response.data.approved_by;
		po_head.value = response.data.po_head;
		po_no.value = response.data.po_head.po_no;
		dr_no.value = response.data.po_dr.dr_no;
		shipping_cost.value = response.data.po_head.shipping_cost;
		handling_fee.value = response.data.po_head.handling_fee;
		discount.value = response.data.po_head.discount;
		vat.value = response.data.po_head.vat;
		vat_percent.value = (response.data.po_head.vat_percent!=0) ? response.data.po_head.vat_percent : 12;
		vat_amount.value = response.data.po_head.vat_amount;
		vat_in_ex.value = response.data.po_head.vat_in_ex;
		newvat.value= (response.data.grand_total + shipping_cost.value + handling_fee.value) * (vat_percent.value/100)
		grand_total.value = (response.data.grand_total + shipping_cost.value + handling_fee.value + newvat.value) - discount.value
		totals.value = response.data.grand_total;
		pr_head.value = response.data.pr_head;
		vendor.value = response.data.po_vendor;
		po_details.value = response.data.po_details;
		vendor_terms.value = response.data.po_terms;
		other_list.value = response.data.po_instructions;
		vendor_details_id.value = response.data.vendor_details_id;
	}

	const ChangeGrandTotal = (vat_percent) => {
		var total=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			total += parseFloat(pi);
        });
		var discount_display= (discount.value!='') ? discount.value : 0;
		var percent= (vat.value==1) ? vat_percent/100 : 0;
		var new_vat= ((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * parseFloat(percent);

		var new_total = (parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value) - parseFloat(discount_display)) + new_vat ;
		grand_total.value = new_total;
		new_data.value=parseFloat(new_total)
		// document.getElementById("vat_amount").value=new_vat.toFixed(2);
		vat_amount.value=new_vat.toFixed(2);

		const prices = document.querySelectorAll('.unit-price');
        let allZero = true;

        prices.forEach(price => {
            if (parseFloat(price.value) !== 0) {
                allZero = false;
            }
        });

        document.getElementById('save').disabled = allZero;
	}

	const vatChange = (vat_percent) => {
		formatter.value = new Intl.NumberFormat('en-US', {
			minimumFractionDigits: 4,      
		})
		var total=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			total += parseFloat(pi);
        });
		var discount_display= (discount.value!='') ? discount.value : 0;
        var percent= (vat.value==1) ? vat_percent/100 : 0;
        var new_vat = ((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * parseFloat(percent);
        document.getElementById("vat_amount").value = new_vat.toFixed(2);
        var new_total=((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) + parseFloat(new_vat);
        grand_total.value=new_total;
		new_data.value=parseFloat(new_total)
	}

	const selectVat = (vat_percent) => {
		formatter.value = new Intl.NumberFormat('en-US', {
			minimumFractionDigits: 4,
		})
		if(vat.value==1){
			var total=0;
			po_details.value.forEach(function (val, index, theArray) {
				var p = document.getElementById('tprice'+index).value;
				var pi = p.replace(",", "");
				total += parseFloat(pi);
			});
			var discount_display= (discount.value!='') ? discount.value : 0;
			var percent=vat_percent/100;
			var new_vat=((parseFloat(total) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * parseFloat(percent);
			vat_amount.value = parseFloat(new_vat).toFixed(2);
			ChangeGrandTotal(vat_percent)
		}else{
			vat_amount.value=0
			ChangeGrandTotal(vat_percent)
		}
	}

	const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
	}

	const checkBalance = async (vat_percent,qty,avail_qty,count) => {
		formatter.value = new Intl.NumberFormat('en-US', {
			minimumFractionDigits: 4,      
		})
		var grandtotal=0;
		po_details.value.forEach(function (val, index, theArray) {
			var p = document.getElementById('tprice'+index).value;
			var pi = p.replace(",", "");
			grandtotal += parseFloat(pi);
        });
		var discount_display= (discount.value!='') ? discount.value : 0;
        var percent= (vat.value==1) ? vat_percent/100 : 0
		var new_vat = ((parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) * parseFloat(percent);
		vat_amount.value=new_vat;
		
		
		var overall_total = ((parseFloat(grandtotal) + parseFloat(shipping_cost.value) + parseFloat(handling_fee.value)) - parseFloat(discount_display)) + parseFloat(new_vat) ;
		grand_total.value=overall_total.toFixed(2);
		if(qty>avail_qty){
			document.getElementById('balance_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = true;
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_draft = document.getElementById("draft");
			btn_draft.disabled = false;
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
		formatter.value = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 4,      
        })
	}

	const CheckItemDescEmpty = (index) => {
		const item =  document.getElementById('itemdesc'+index).value;
		if(item === ''){
			document.getElementById('itemdesc'+index).style.backgroundColor = '#FAA0A0';
			document.getElementById("draft").disabled = true
			document.getElementById("save").disabled = true
		}else{
			document.getElementById('itemdesc'+index).style.backgroundColor = '#FEFCE8';
			document.getElementById("draft").disabled = false
			document.getElementById("save").disabled = false
		}
	}

	const onSave = (status) => {
		const formData= new FormData()
		var total = String(grand_total.value);
		var total_replace = total.replace(",", "");
		formData.append('dr_no', dr_no.value)
		formData.append('po_no', po_no.value)
		formData.append('pr_no', pr_head.value.pr_no)
		formData.append('vendor_details_id', vendor_details_id.value)
		formData.append('shipping_cost', shipping_cost.value)
		formData.append('handling_fee', handling_fee.value)
		formData.append('discount', discount.value)
		formData.append('vat', vat.value)
		formData.append('vat_percent', (vat.value!=0) ? vat_percent.value : 0)
		formData.append('vat_amount', vat_amount.value)
		formData.append('vat_in_ex', vat_in_ex.value)
		formData.append('grand_total', total_replace)
		formData.append('checked_by', checked_by.value)
		formData.append('approved_by', approved_by.value)
		formData.append('recommended_by', recommended_by.value)
		formData.append('terms_list', JSON.stringify(terms_list.value))
		formData.append('vendor_terms', JSON.stringify(vendor_terms.value))
		formData.append('other_list', JSON.stringify(other_list.value))
		formData.append('po_details', JSON.stringify(po_details.value))
		formData.append('po_head_id', pohead_id.value)
		formData.append('props_id', props.id)
		formData.append('status', status)

		// po_details.value.forEach(function (val, index, theArray) {
		// 	formData.append('quantity'+index, 'available_qty'+index)
		// });
		if(status==='Saved'){
			if(checked_by.value!=0 && approved_by.value!=0 && recommended_by.value!=0 && vat_in_ex.value!=0){
				axios.post(`/api/save_direct_po`,formData).then(function (response) {
					pohead_id.value=response.data;
					success.value='You have successfully saved new po.'
					successAlertCD.value=!successAlertCD.value
					setTimeout(() => {
						if(props.id==0){
							router.push('/pur_po/view/'+pohead_id.value)
						}else{
							router.push('/pur_po/view/'+props.id)
						}
					}, 2000);
				}, function (err) {
					error.value='Error! Please try again.';
					dangerAlerterrors.value=!dangerAlerterrors.value
				}); 
			}else{
				if(vat_in_ex.value==0){
					document.getElementById('vat_in_ex').style.backgroundColor = '#FAA0A0';
				}
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
			axios.post(`/api/save_direct_po`,formData).then(function (response) {
				pohead_id.value=response.data;
				success.value='You have successfully draft new po.'
				warningAlert.value=!warningAlert.value
				if(props.id!=0){
					dpoDraft()
				}else{
					terms_list.value=[]
					other_list.value=[]
					dpoDraftDisplay()
				}
			}, function (err) {
				error.value='Error! Please try again.';
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}
    }

	const showModal = ref(false)
	const hideModal = ref(true)
	const showAddVendor = ref(false)
	const showPreview = ref(false)
	
	const openModel = () => {
		showModal.value = !showModal.value
	}
	const closeModal = () => {
		showModal.value = !hideModal.value
	}
	const error =  ref('');
	const success =  ref('');
	const dangerAlerterrors = ref(false)
	const dangerAlert = ref(false)
	const dangerAlert_terms = ref(false)
	const dangerAlert_instructions = ref(false)
	const successAlert = ref(false)
	const successAlertCD = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
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
		successAlertCD.value = !hideAlert.value
		dangerAlerterrors.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert_terms.value = !hideAlert.value
		dangerAlert_instructions.value = !hideAlert.value
		warningAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
	}

	const pr_det = ref(false)
	const pr_supplier = ref(false)
	const openPR = () => {
		pr_det.value = !hideAlert.value
		pr_supplier.value = !pr_supplier.value
	}

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
			axios.get(`/api/delete_dpo_terms/`+id).then(function () {
				dangerAlert_terms.value = !hideAlert.value
				success.value='Successfully deleted term!'
				successAlertCD.value = !successAlertCD.value
				dpoDraft()
				terms_list.value=[]
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
			dpoDraftDisplay()
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
			axios.get(`/api/delete_dpo_instructions/`+id).then(function () {
				dangerAlert_instructions.value = !hideAlert.value
				success.value='Successfully deleted instruction!'
				successAlertCD.value = !successAlertCD.value
				dpoDraft()
				other_list.value=[]
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
			dpoDraftDisplay()
		}else{
			instruction_id.value=id
			dangerAlert_instructions.value = !dangerAlert_instructions.value
		}
	}

	const cancelAllPO = (option) => {
		if(option=='yes'){
			if(cancel_all_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_all_reason', cancel_all_reason.value)
				axios.post(`/api/cancel_all_po/`+pohead_id.value,formData).then(function (response) {
                    dangerAlert.value = !hideAlert.value
                    success.value='Successfully cancelled PO!'
                    successAlertCD.value = !successAlertCD.value
                    cancel_all_reason.value=''
                    document.getElementById('cancel_all_check').placeholder=""
                    document.getElementById('cancel_all_check').style.backgroundColor = '#FFFFFF';
                    dpoDraft()
                    router.push('/pur_po/view/'+pohead_id.value)
				})
			}else{
				document.getElementById('cancel_all_check').placeholder="Cancel Reason must not be empty!"
				document.getElementById('cancel_all_check').style.backgroundColor = '#FAA0A0';
			}
		}else{
			dangerAlert.value = !dangerAlert.value
		}
	}

	const resetError = (button) => {
		if(button==='button1'){
			document.getElementById('checked_by').style.backgroundColor = '#FEFCE8';
		}
		if(button==='button2'){
			document.getElementById('recommended_by').style.backgroundColor = '#FEFCE8';
		}
		if(button==='button3'){
			document.getElementById('approved_by').style.backgroundColor = '#FEFCE8';
		}
		if(button==='button4'){
			document.getElementById('vat_in_ex').style.backgroundColor = '#FEFCE8';
		}
		const btn_draft = document.getElementById("draft");
		btn_draft.disabled = false;
		const btn_save = document.getElementById("save");
		btn_save.disabled = false;
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
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600" v-if="props.id==0">Direct PO <small >New</small></h3>
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600" v-else>Direct PO <small >Draft</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_direct">Direct PO</a></li>
							<li class="breadcrumb-item active" aria-current="page" v-if="props.id==0">New</li>
                            <li class="breadcrumb-item active" aria-current="page" v-else>Draft</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<!-- <span class="pt-2">
							<h3 class="card-title !text-lg m-0">Request for Quotation <small>New</small></h3>
						</span> -->
						<hr class="border-dashed mt-0">
						<div class="pt-1">
							<div class="row">							
								<div class="col-lg-6 offset-lg-3 col-md-3">
									<div class="form-group">
									<label class="text-gray-500 m-0" for="">Choose PR Number & Supplier</label>
									<input type="file" name="img[]" class="file-upload-default">
									<div class="input-group col-xs-12">
										<select class="form-control file-upload-info" v-model="pr_no" @change="getSupplierDPR()">
											<option value="">--Select PR Number--</option>
											<option :value="p.pr_no" v-for="p in prno_dropdown" :key="p.pr_no">{{ p.pr_no }}</option>
										</select>
										<select class="form-control file-upload-info" id="dpr_supplier" v-model="vendor_details_id" @change="OpenBtn()" disabled>
											<option value="">--Select Supplier--</option>
											<option :value="sup.id" v-for="sup in suppliers" :key="sup.id">{{ sup.vendor_name }} ({{ sup.identifier }})</option>
										</select>
										<span class="input-group-append">
											<button class="btn btn-primary" type="button" id="generatepobtn" @click="generatePO()" disabled>Select</button>
										</span>
									</div>
									</div>
								</div>
							</div>
							<hr class="border-dashed">
							<!-- <div class="hidden" :class="{ show:pr_supplier }">
								<div class="row">							
									<div class="col-lg-6 offset-lg-3 col-md-3">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Choose Supplier</label>
											<div class="input-group col-xs-12 !text-gray-600">
												<select class="form-control !text-gray-600">
													<option value="">Supplier 1</option>
													<option value="">Supplier 2</option>
												</select>
												<span class="input-group-append">
													<button class="btn btn-primary" type="button">Select</button>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div> -->
							<!-- <div class="hidden" :class="{ show:pr_det }"> -->
							<div v-if="po_head != ''">
								<!-- <div class="row">
									<div class="col-lg-6">
										<select class="form-control !text-gray-600 !w-96 mb-1 !bg-yellow-50">
											<option value="">Supplier 1</option>
											<option value="">Supplier 2</option>
										</select>
									</div>
								</div> -->
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
										<span class="text-sm text-gray-700">
											<!-- <input type="hidden" v-model="dr_no"> -->
											<input type="hidden" v-model="po_no">
											{{ po_no }}
										</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
										<span class="text-sm text-gray-700">{{ moment().format('MMM. DD,YYYY') }}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">Supplier: </span>
										<span class="text-sm text-gray-700">{{ vendor.vendor_name }} ({{ vendor.identifier }})</span>
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
								<br>
								<div class="row">
									<div class="col-lg-12">
										<table class="w-full table-bordered !text-xs mb-0">
											<tr class="bg-gray-100">
												<td class="p-1 uppercase text-center" width="2%">#</td>
												<td class="p-1 uppercase text-center" width="5%">Qty</td>
												<td class="p-1 uppercase text-center" width="5%">UOM</td>
												<td class="p-1 uppercase" >Description</td>
												<td class="p-1 text-center" width="10%">Unit Price</td>
												<td class="p-1 text-center" width="10%">Total</td>
											</tr>
											<tr v-for="(pd, index) in po_details">
												<span hidden>{{ totalprice=formatter.format(pd.unit_price * pd.quantity) }}</span>
												<td class="align-top p-1 text-center">{{ index+1}}</td>
												<td class="align-top p-0 bg-yellow-50">
													<input type="text" class="p-1 text-center w-full bg-yellow-50" :id="'balance_checker'+ index" placeholder="00.00" @keyup="checkBalance(vat_percent,pd.quantity,pd.available_qty, index)" step="any" @keypress="isNumber($event)" v-model="pd.quantity">
												</td>
												<td class="align-top p-0 text-center">{{ pd.uom }}
												</td>
												<td class="align-top p-0 bg-yellow-50">
													<textarea name="" :id="'itemdesc'+index" rows="2" class="p-1 w-full bg-yellow-50 itemdesc" v-model="pd.item_description" @change="CheckItemDescEmpty(index)"></textarea>
												</td>
												<td class="align-top p-0 bg-yellow-50">
													<input type="text" class="p-1 text-right w-full bg-yellow-50 border-b unit-price" :id="'po_unitprice'+ index" placeholder="00.00" @keypress="isNumber($event)" @change="ChangeGrandTotal(vat_percent)" v-model="pd.unit_price">
													<select class="p-1 m-0 leading-none w-full text-center  bg-yellow-50" v-model="pd.currency">
														<option v-for="cur in currency" v-bind:key="cur" v-bind:value="cur">{{ cur }}</option>
													</select>
												</td>
												<td class="align-top p-0 text-right">
													 <input type="text" class="text-center tprice" :id="'tprice'+index" v-model="totalprice" readonly>
												</td>
											</tr>
											<tr class="">
												<td class=""></td>
												<td class=""></td>
												<td class=""></td>
												<td class=""></td>
												<td class=""></td>
												<td class=""></td>
											</tr>
											<tr class="">
												<td class="border-r-none align-top p-2" colspan="3" width="65%" rowspan="5">
													<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>{{pr_head.pr_no}}</p>
													<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>{{pr_head.requestor}}</p>
													<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>{{pr_head.enduse}}</p>

												</td>
												<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
												<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-0.5 text-right pr-1"  @keypress="isNumber($event)"  @keyup="ChangeGrandTotal(vat_percent)" @change="ChangeGrandTotal(vat_percent)" v-model="shipping_cost"></td>
											</tr>
											<tr class="">
												<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
												<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right"  @keypress="isNumber($event)"  @keyup="ChangeGrandTotal(vat_percent)" @change="ChangeGrandTotal(vat_percent)" v-model="handling_fee"></td>
											</tr>
											<tr class="">
												<td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
												<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right"  @keypress="isNumber($event)"  @keyup="ChangeGrandTotal(vat_percent)" @change="ChangeGrandTotal(vat_percent)" v-model="discount"></td>
											</tr>
											<tr class="">
												<td class="border-l-none border-y-none p-0 text-right bg-yellow" colspan="2">
													<div class="flex justify-end">
														<!-- <span class="p-1" >VAT</span> -->
														<select name="" class="border px-1 text-xs" id="" @change="selectVat(vat_percent)" v-model="vat">
															<option value="0">--Select--</option>
															<option value="1">VAT</option>
															<option value="2">NON-VAT</option>
														</select>
													</div>
												</td>
												<!-- Kamo na bahala mag hide sang duwa ka input sa dalom kung Non VAT-->
												<!-- <td class="p-0">
														<input type="text" class="w-10 bg-yellow-50 border-r text-center" value="12%">
														<input type="text" class="w-10 bg-yellow-50 border-r text-center" value="12" hidden>
														<input type="text" class="w-full bg-yellow-50 p-1 text-right" value="">
												</td> -->
												<!-- VAT -->
												<td class="p-0" v-if="vat==1">
													<div class="flex p-0">
														<input type="number" min="0" class="w-10 bg-yellow-50 border-r text-center" v-model="vat_percent" id="vat_percent" @keyup="vatChange(vat_percent)" @change="vatChange(vat_percent)">%
														<input type="text" class="w-10 bg-yellow-50 border-r text-center" value="12" hidden>
														<input type="number" min="0" step="any" @keypress="isNumber($event)" class="w-full bg-yellow-50 p-1 text-right" id="vat_amount" v-model="vat_amount" @keyup="ChangeGrandTotal(vat_percent)" @change="ChangeGrandTotal(vat_percent)">
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
												<td class="p-1 text-right font-bold !text-sm">{{ formatter.format(grand_total) }}</td>
											</tr>
										</table>
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
														<select name="" class="w-full bg-yellow-50" id="vat_in_ex" v-model="vat_in_ex" @click="resetError('button4')">
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
								<hr	class="border-dashed">
								<div class="row my-2"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-center space-x-2">
											<!-- <button type="submit" class="btn btn-warning text-white mr-2 w-36" @click="openWarningAlert()">Save as Draft</button>
											<button type="submit" class="btn btn-primary mr-2 w-44" @click="openSuccessAlert()">Save</button> -->
											<button type="button" class="btn btn-danger w-36"  @click="cancelAllPO('no')" v-if="pohead_id!=0">Cancel PO</button>
											<button @click="onSave('Draft')" class="btn btn-warning w-26 !text-white" id="draft">Save as Draft</button>
											<button @click="onSave('Saved')" type="button" class="btn btn-primary w-36" id="save" disabled>Save</button>
										</div>
									</div>
								</div>
							</div>
							<div v-else>
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
									<a href="/po_direct/0" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a>
									<a :href="'/pur_po/view/'+pohead_id" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a>
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
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="bg-green-500 btn-sm !rounded-full w-full"  @click="closeAlert()">Close</button>
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
									<a href="/po_direct/0" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
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
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelAllPO('yes')">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>