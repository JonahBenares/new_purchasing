<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();

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
									<select class="form-control file-upload-info">
										<option value="">Select Supplier</option>
										<option value="">MF Computer Solutions, Inc. </option>
									</select>
									<select class="form-control file-upload-info">
										<option value="">PR Number</option>
										<option value="">PR-19772-8727</option>
									</select>
									<span class="input-group-append">
										<button class="btn btn-primary" type="button" @click="pr_det = !pr_det">Select</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<hr class="border-dashed">
					<div class="pt-1">
						<div v-show="pr_det">
							<div class="row">
								<div class="col-lg-8">
									<span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
									<span class="text-sm text-gray-700">PO-CENPRI24-1001</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">05/16/24</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8">
									<span class="text-sm text-gray-700 font-bold pr-1">Supplier: </span>
									<span class="text-sm text-gray-700">MF Computer Solutions, Inc.</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8">
									<span class="text-sm text-gray-700 font-bold pr-1">Address:</span>
									<span class="text-sm text-gray-700">February 16, 2024</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Telephone: </span>
									<span class="text-sm text-gray-700">(034) 9872-2772</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8">
									<span class="text-sm text-gray-700 font-bold pr-1">Contact Person: </span>
									<span class="text-sm text-gray-700">Mary Marie</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Telefax: </span>
									<span class="text-sm text-gray-700">(034) 9872-2772</span>
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
												<tr class="">
													<td class="border-y-none p-1 text-center">1</td>
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">pc</td>
													<td class="border-y-none p-1" colspan="2">Monitor</td>
													<td class="border-y-none p-1 text-right">100.00</td>
													<td class="border-y-none p-1 text-right">500.00</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1 text-center">2</td>
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">pc</td>
													<td class="border-y-none p-1" colspan="2">Mouse</td>
													<td class="border-y-none p-1 text-right">100.00</td>
													<td class="border-y-none p-1 text-right">500.00</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1 text-center">3</td>
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">pc</td>
													<td class="border-y-none p-1" colspan="2">Keyboard</td>
													<td class="border-y-none p-1 text-right">100.00</td>
													<td class="border-y-none p-1 text-right">500.00</td>
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
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>PR-19772-8727</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
													</td>
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
												<button type="submit" class="btn btn-warning w-26 !text-white" >Save as Draft</button>
												<a href="/pur_po/view" type="submit" class="btn btn-primary w-36">Save</a>
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
	</navigation>
	
</template>