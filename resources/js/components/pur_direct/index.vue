<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, MagnifyingGlassIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"

	const showModal = ref(false)
	const hideModal = ref(true)
	const openModel = () => {
		showModal.value = !showModal.value
	}
	const closeModal = () => {
		showModal.value = !hideModal.value
	}
	const dangerAlert = ref(false)
	const successAlert = ref(false)
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
		dangerAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		warningAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
	}

	const pr_det = ref(false)
	const pr_supplier = ref(false)
	const openPR = () => {
		pr_det.value = !hideAlert.value
		pr_supplier.value = !pr_supplier.value
	}
	let vendor_list=ref([]);
	let vendor_name=ref('');
	let terms_list=ref([]);
	let terms_text=ref("");
	let other_list=ref([]);
	let other_text=ref("");

	const addVendor= () => {
		for(var x=0; x<vendor_list.value.length; x++){
			if(document.getElementById("v_name"+x).value == vendor_name.value){
				var vendor_count = 1;
			}
		}

			if(vendor_count != undefined){
				alert("The vendor is already added!")
			}else if(vendor_name.value == ''){
				alert("You must select Vendor!")
			}else{
				const vendors = {
					vendor_name:vendor_name.value,
					vname:vendor_name.value,
				}
				vendor_list.value.push(vendors)
				vendor_name.value='';

				// vendor_list.value.forEach(function (val, index, theArray) {
				// 	if(document.getElementById("v_name"+index).value == vendor_name.value){
				// 		alert("This vendor is already added!")
				// 		vendor_list.value.splice(index,1)
				// 	}
				// });
				// vendor_name.value='';
			}
	}

	const removeVendor = (index) => {
		vendor_list.value.splice(index,1)
	}

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
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Direct PO <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_direct">Direct PO</a></li>
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
						<!-- <span class="pt-2">
							<h3 class="card-title !text-lg m-0">Request for Quotation <small>New</small></h3>
						</span> -->
						<hr class="border-dashed mt-0">
						<div class="pt-1">
							<div class="row">							
								<div class="col-lg-6 offset-lg-3 col-md-3">
									<div class="form-group">
									<label class="text-gray-500 m-0" for="">Choose PR Number</label>
									<input type="file" name="img[]" class="file-upload-default">
									<div class="input-group col-xs-12">
										<select class="form-control file-upload-info">
											<option value="">PR-CENPRI24-1001</option>
											<option value="">PR-CENPRI24-1002</option>
										</select>
										<span class="input-group-append">
											<button class="btn btn-primary" type="button" @click="pr_det =!pr_det">Select</button>
										</span>
									</div>
									</div>
								</div>
							</div>
							<hr class="border-dashed">
							<div class="hidden" :class="{ show:pr_supplier }">
								<div class="row">							
									<div class="col-lg-6 offset-lg-3 col-md-3">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Choose Supplier</label>
											<div class="input-group col-xs-12 !text-gray-600">
												<select class="form-control !text-gray-600">
													<option value="">Suppleir 1</option>
													<option value="">Supplier 2</option>
												</select>
												<span class="input-group-append">
													<button class="btn btn-primary" type="button">Select</button>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="hidden" :class="{ show:pr_det }">
								<div class="row">
									<div class="col-lg-6">
										<select class="form-control !text-gray-600 !w-96 mb-1">
											<option value="">Suppleir 1</option>
											<option value="">Supplier 2</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<span class="text-sm text-gray-700 font-bold pr-1">Purchase Request: </span>
										<span class="text-sm text-gray-700">Bacolod</span>
									</div>
									<div class="col-lg-6">
										<span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
										<span class="text-sm text-gray-700">01/16/24</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<span class="text-sm text-gray-700 font-bold pr-1">PR Number: </span>
										<span class="text-sm text-gray-700">PR-BCD24-1209</span>
									</div>
									<div class="col-lg-6">
										<span class="text-sm text-gray-700 font-bold pr-1">New PR Number: </span>
										<span class="text-sm text-gray-700">PR-CENPRI24-1002</span>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-6">
										<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
										<span class="text-sm text-gray-700">IT Department</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
										<span class="text-sm text-gray-700">0912</span>
									</div>
									<div class="col-lg-2">
										<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
										<span class="text-sm text-gray-700">X</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
										<span class="text-sm text-gray-700">IT Department</span>
									</div>
									<div class="col-lg-12">
										<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
										<span class="text-sm text-gray-700">Replace damage monitor, mouse and keyboard</span>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-lg-12">
										<table class="w-full table-bordered !text-xs mb-0">
											<tr class="bg-gray-100">
												<td class="p-1 uppercase text-center" width="2%">#</td>
												<td class="p-1 uppercase text-center" width="9%">Qty</td>
												<td class="p-1 uppercase text-center" width="9%">UOM</td>
												<td class="p-1 uppercase" >Description</td>
												<td class="p-1 text-center" width="15%">Unit Price</td>
												<td class="p-1 text-center" width="15%">Total</td>
											</tr>
											<tr>
												<td class="align-top p-1 text-center">1</td>
												<td class="align-top p-0 bg-yellow-50">
													<input type="text" class="p-1 text-center w-full bg-yellow-50" placeholder="00">
												</td>
												<td class="align-top p-0 bg-yellow-50">
													<input type="text" class="p-1 text-center w-full bg-yellow-50" placeholder="uom">
												</td>
												<td class="align-top p-0 bg-yellow-50">
													<textarea name="" id="" rows="2" class="p-1 w-full bg-yellow-50">Monitor,</textarea>
												</td>
												<td class="align-top p-0 bg-yellow-50">
													<input type="text" class="p-1 text-right w-full bg-yellow-50" placeholder="00">
												</td>
												<td class="align-top p-0 bg-yellow-50">
													<input type="text" class="p-1 text-right w-full bg-yellow-50" placeholder="00">
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
												<td class="border-r-none align-top p-2" colspan="3" width="65%" rowspan="5"></td>
												<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
												<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-0.5 text-right pr-1" value="200.00"></td>
											</tr>
											<tr class="">
												<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
												<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right" value="200.00"></td>
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
												<td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
												<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right" value="100.00"></td>
											</tr>
											<tr class="">
												<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
												<td class="p-1 text-right font-bold !text-sm">1000.00</td>
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
														<span class="w-32">Payment </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">5.</td>
												<td class="align-top  pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-32">Item Warranty </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">6.</td>
												<td class="align-top  pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-32">Delivery Time </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">7.</td>
												<td class="align-top  pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-32">Freight </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr v-for="(t,index) in terms_list">
												<td class="align-top text-center" width="4%">{{ index + 8 }}.</td>
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
												<td class="text-center p-1"><input type="text" class="text-center" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-1"><input type="text" class="text-center" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-1"><input type="text" class="text-center" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-1"><input type="text" class="text-center" placeholder="Employee Name"></td>
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
											<button type="submit" class="btn btn-warning text-white mr-2 w-36" @click="openWarningAlert()">Save as Draft</button>
											<button type="submit" class="btn btn-primary mr-2 w-44" @click="openSuccessAlert()">Save</button>
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
									<h5 class="leading-tight">You have successfully created a Direct PO.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/po_direct" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a>
									<a href="/po_direct/view" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a>
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
									<h5 class="leading-tight">You have successfully saved a Direct PO as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/po_direct" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>