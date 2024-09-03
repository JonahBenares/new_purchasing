<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();

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

</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Abstract of Quotation <small>Award</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_aoq">Abstract of Quotation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Award</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="bg-yellow-400 text-white px-3 py-2 font-bold">Done TE</div>
		<div class="row">
			
			<div class="col-12 grid-margin stretch-card">
				
				<div class="card">
				<div class="card-body">
					<!-- <span class="pt-2">
						<h3 class="card-title !text-lg m-0">Request for Quotation <small>New</small></h3>
					</span> -->
					<hr class="border-dashed mt-0">
					<div class="pt-1">
						<div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">PR No: </span>
									<span class="text-sm text-gray-700">PR-CENPRI24-1002</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">AOQ No: </span>
									<span class="text-sm text-gray-700">AOQ-1009-1001</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Requested By: </span>
									<span class="text-sm text-gray-700">Henne Tanan</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">IT Department</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">05/16/24</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date Needed: </span>
									<span class="text-sm text-gray-700">05/16/24</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use:</span>
									<span class="text-sm text-gray-700">February 16, 2024</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">Replace damage monitor, mouse and keyboard</span>
								</div>
							</div>


							<div class="second_one" v-if="vendor === 'vendor_2'">
								<br>
								<div class="row">
									<div class="col-lg-12">
										<table class="table-bordered w-full !text-xs">
											<tr>
												<td class="bg-gray-50 " colspan="4"></td>
												<!-- loop vendors here start -->
												<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
													<p class="m-0 text-xs font-bold">MF Computer Solutions, Inc. </p>
													<!-- <p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
													<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
													<div class="flex justify-center space-x-2">
														<span>Mary Marie</span>
														<span>|</span>
														<span>(034) 9872-2772</span>
													</div>
												</td>
												<!-- loop vendors here end-->
											</tr>
											<tr>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="3%">#</td>
												<td class="uppercase bg-gray-100 p-1 align-top" width="30%">Item Description</td>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="5%">Qty</td>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="5%">UOM</td>
												<!-- loop offer header per vendor here start -->
												<td class="uppercase bg-gray-100 p-1 " >Offer</td>
												<td class="uppercase bg-gray-100 p-1 text-center" >Unit Price</td>
												<td class="uppercase bg-gray-100 p-1 text-center" colspan="2">Amount</td>
												<td class="uppercase bg-gray-100 p-1 text-center" >Comment</td>
												<!-- loop offer header per vendor here end-->
											</tr>
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">1</td>
													<td class="p-1 align-top" rowspan="4">Monitor</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top bg-yellow-300" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>100.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2a" >
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>250.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1250.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2a" >
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>350.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2a" >
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">2</td>
													<td class="p-1 align-top" rowspan="4">Mouse</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top bg-yellow-300" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>100.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>250.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1250.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>350.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">3</td>
													<td class="p-1 align-top" rowspan="4">Keyboard</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top bg-yellow-300" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>100.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>250.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1250.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>350.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_2c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
											<tr class="!border-0">
												<td class="!border-0" colspan="4"><br></td>
												<td class="!border-0" colspan="4"><br></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">a.</td>
												<td class="!border-0 text-center" colspan="1">Price Validity</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan="4"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">b.</td>
												<td class="!border-0 text-center" colspan="1">Payment Terms</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan=""></td>
												<td class="!border-0 text-right px-2" colspan="">Legend:</td>
												<td class="!border-0" colspan="2"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">c.</td>
												<td class="!border-0 text-center" colspan="1">Delivery Term</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
												<td class="!border-0 bg-lime-500" colspan="1"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">d.</td>
												<td class="!border-0 text-center" colspan="1">Item's Warranty</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
												<td class="!border-0 bg-yellow-300" colspan="1"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">e.</td>
												<td class="!border-0 text-center" colspan="1">In-land Freight</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan="4"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0" colspan="4"><br></td>
												<td class="!border-0" colspan="4"><br></td>
											</tr>
										</table>
									</div>
								</div>
								<br>
								<div class="row my-2"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-between space-x-2">
											<div class="flex justify-between space-x-1">
												<button type="submit" @click="openPreview()" class="btn btn-info w-26">Preview</button>
												<button type="submit" @click="openAddVendor()" class="btn btn-info w-26">Add Vendor</button>
											</div>
											<div class="flex justify-between space-x-1">
												<button type="submit" class="btn btn-warning w-26 !text-white" @click="openWarningAlert()">Save as Draft</button>
												<button type="submit" class="btn btn-primary w-26" v-on:click="vendor = 'vendor_1'">Back</button>
												<button type="submit" class="btn btn-primary w-26" v-on:click="vendor = 'vendor_3'">Next</button>
											</div>
											
										</div>
									</div>
								</div>
							</div>
							
							<div class="second_one" v-else-if="vendor === 'vendor_3'">
								<br>
								<div class="row">
									<div class="col-lg-12">
										<table class="table-bordered w-full !text-xs">
											<tr>
												<td class="bg-gray-50 " colspan="4"></td>
												<!-- loop vendors here start -->
												<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
													<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
													<!-- <p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
													<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
													<div class="flex justify-center space-x-2">
														<span>Mary Marie</span>
														<span>|</span>
														<span>(034) 9872-2772</span>
													</div>
												</td>
												<!-- loop vendors here end-->
											</tr>
											<tr>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="3%">#</td>
												<td class="uppercase bg-gray-100 p-1 align-top" width="30%">Item Description</td>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="5%">Qty</td>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="5%">UOM</td>
												<!-- loop offer header per vendor here start -->
												<td class="uppercase bg-gray-100 p-1 " >Offer</td>
												<td class="uppercase bg-gray-100 p-1 text-center" >Unit Price</td>
												<td class="uppercase bg-gray-100 p-1 text-center" colspan="2">Amount</td>
												<td class="uppercase bg-gray-100 p-1 text-center" >Comment</td>
												<!-- loop offer header per vendor here end-->
											</tr>
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">1</td>
													<td class="p-1 align-top" rowspan="4">Monitor</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top bg-yellow-300" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>100.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3a">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>250.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1250.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3a">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>350.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3a">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">2</td>
													<td class="p-1 align-top" rowspan="4">Mouse</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>150.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>200.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1000.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>300.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">3</td>
													<td class="p-1 align-top" rowspan="4">Keyboard</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top bg-yellow-300" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>100.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>250.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1250.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>350.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_3c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
											<tr class="!border-0">
												<td class="!border-0" colspan="4"><br></td>
												<td class="!border-0" colspan="4"><br></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">a.</td>
												<td class="!border-0 text-center" colspan="1">Price Validity</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan="4"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">b.</td>
												<td class="!border-0 text-center" colspan="1">Payment Terms</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan=""></td>
												<td class="!border-0 text-right px-2" colspan="">Legend:</td>
												<td class="!border-0" colspan="2"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">c.</td>
												<td class="!border-0 text-center" colspan="1">Delivery Term</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
												<td class="!border-0 bg-lime-500" colspan="1"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">d.</td>
												<td class="!border-0 text-center" colspan="1">Item's Warranty</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
												<td class="!border-0 bg-yellow-300" colspan="1"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">e.</td>
												<td class="!border-0 text-center" colspan="1">In-land Freight</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan="4"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0" colspan="4"><br></td>
												<td class="!border-0" colspan="4"><br></td>
											</tr>
										</table>
									</div>
								</div>
								<br>
								<div class="row my-2"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-between space-x-2">
											<div class="flex justify-between space-x-1">
												<button type="submit" @click="openPreview()" class="btn btn-info w-26">Preview</button>
												<button type="submit" @click="openAddVendor()" class="btn btn-info w-26">Add Vendor</button>
											</div>
											<div class="flex justify-between space-x-1">
												<button type="submit" class="btn btn-warning w-26 !text-white" @click="openWarningAlert()">Save as Draft</button>
												<button type="submit" class="btn btn-primary w-26" v-on:click="vendor = 'vendor_2'">Back</button>
												<button type="submit" class="btn btn-primary w-26" @click="openSuccessAlert()">Save AOQ</button>
											</div>
											
										</div>
									</div>
								</div>
							</div>

							<div class="first_one" v-else>
								<br>
								<div class="row">
									<div class="col-lg-12">
										<table class="table-bordered w-full !text-xs">
											<tr>
												<td class="bg-gray-50 " colspan="4"></td>
												<!-- loop vendors here start -->
												<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
													<p class="m-0 text-xs font-bold">Lectrix Solutions Electrical Supplies & Services Cebu</p>
													<!-- <p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
													<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
													<div class="flex justify-center space-x-2">
														<span>Mary Marie</span>
														<span>|</span>
														<span>(034) 9872-2772</span>
													</div>
												</td>
												<!-- loop vendors here end-->
											</tr>
											<tr>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="3%">#</td>
												<td class="uppercase bg-gray-100 p-1 align-top" width="30%">Item Description</td>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="5%">Qty</td>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="5%">UOM</td>
												<!-- loop offer header per vendor here start -->
												<td class="uppercase bg-gray-100 p-1 " >Offer</td>
												<td class="uppercase bg-gray-100 p-1 text-center" >Unit Price</td>
												<td class="uppercase bg-gray-100 p-1 text-center" colspan="2">Amount</td>
												<td class="uppercase bg-gray-100 p-1 text-center" >Comment</td>
												<!-- loop offer header per vendor here end-->
											</tr>
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">1</td>
													<td class="p-1 align-top" rowspan="4">Monitor</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>150.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1a">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>200.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1000.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1a">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>300.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1a">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">2</td>
													<td class="p-1 align-top" rowspan="4">Mouse</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top bg-yellow-300" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>100.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>250.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1250.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>350.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1b">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">3</td>
													<td class="p-1 align-top" rowspan="4">Keyboard</td>
													<td class="p-1 align-top text-center" rowspan="4">5</td>
													<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">
																sample 1
															</td>
															<td class="p-1 align-top bg-yellow-300" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>100.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>500.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">sample 2</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>250.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1250.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
													<tr>
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">smaple 3</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>350.00</span>
																</div>
															</td>
															<td class="p-1 align-top" width="11%">
																<div class="flex justify-between space-x-1">
																	<span>Php</span>
																	<span>1750.00</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%">
																<input type="radio" name="offer_1c">
															</td>
															<td class="p-1 align-top" width="10%">
																<textarea placeholder="Comments" class="w-full" rows="1"></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
											<tr class="!border-0">
												<td class="!border-0" colspan="4"><br></td>
												<td class="!border-0" colspan="4"><br></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">a.</td>
												<td class="!border-0 text-center" colspan="1">Price Validity</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan="4"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">b.</td>
												<td class="!border-0 text-center" colspan="1">Payment Terms</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan=""></td>
												<td class="!border-0 text-right px-2" colspan="">Legend:</td>
												<td class="!border-0" colspan="2"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">c.</td>
												<td class="!border-0 text-center" colspan="1">Delivery Term</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
												<td class="!border-0 bg-lime-500" colspan="1"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">d.</td>
												<td class="!border-0 text-center" colspan="1">Item's Warranty</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
												<td class="!border-0 bg-yellow-300" colspan="1"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0 text-center">e.</td>
												<td class="!border-0 text-center" colspan="1">In-land Freight</td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1"></td>
												<td class="!border-0" colspan="4"></td>
											</tr>
											<tr class="!border-0">
												<td class="!border-0" colspan="4"><br></td>
												<td class="!border-0" colspan="4"><br></td>
											</tr>
										</table>
									</div>
								</div>
								<br>
								<div class="row my-2"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-between space-x-2">
											<div class="flex justify-between space-x-1">
												<button type="submit" @click="openPreview()" class="btn btn-info w-26">Preview</button>
												<button type="submit" @click="openAddVendor()" class="btn btn-info w-26">Add Vendor</button>
											</div>
											<div class="flex justify-between space-x-1">
												<!-- <button type="submit" class="btn btn-primary w-26">Back</button> -->
												<button type="submit" class="btn btn-warning w-26 !text-white" @click="openWarningAlert()">Save as Draft</button>
												<button type="submit" class="btn btn-primary w-26" v-on:click="vendor = 'vendor_2'">Next</button>
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
			<div class="modal pt-4 px-3" :class="{ show:showAddVendor }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-8/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Vendor</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Vendor</label>
									<select type="text" class="form-control" placeholder="Vendor" value="">
										<option value="">Select Vendor</option>
										<option value="">Tough Performance AutoWorkz</option>
										<option value="">2GO Express, Inc.</option>
										<option value="">4 Sisters Sack's Trading</option>
										<option value="">A-1 Gas Corporation</option>
									</select>
								</div>
								<table class="table-bordered w-full !text-xs">
									<tr>
										<td class="p-1 text-center" width="5%">No</td>
										<td class="p-1 text-center" width="10%">Qty</td>
										<td class="p-1" width="35%">Item Description</td>
										<td class="p-1" width="35%">Brand/Offer</td>
										<td class="p-1 text-center" width="15%">Unit Price</td>
									</tr>
									<tr>
										<td class="p-1 align-top text-center">1</td>
										<td class="p-1 align-top text-center">5</td>
										<td class="p-1 align-top">Monitor</td>
										<td class="align-top">
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="p-1 align-top text-center">1</td>
										<td class="p-1 align-top text-center">5</td>
										<td class="p-1 align-top">Mouse</td>
										<td class="align-top">
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="p-1 align-top text-center">1</td>
										<td class="p-1 align-top text-center">5</td>
										<td class="p-1 align-top">Keyboard</td>
										<td class="align-top">
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/pur_quote/print" class="btn btn-primary mr-2 w-44">Save</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
		<Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal pt-4 px-3" :class="{ show:showPreview }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-10/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Preview AOQ</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<div class="modal_s_items">
						<div class="overflow-x-scroll">
							<div class="">
								<table class="w-full !text-xs mb-3 ">
									<tr>
										<td class="font-bold pr-1" width="8%">PR No: </td>
										<td class="">PR-CENPRI24-1002</td>
										<td class=" font-bold pr-1" width="8%">AOQ No: </td>
										<td class="">AOQ-1009-1001</td>
										<td class=" font-bold pr-1" width="8%">Requested By: </td>
										<td class="">Henne Tanan</td>
									</tr>
									<tr>
										<td class="font-bold pr-1">Department: </td>
										<td class="">IT Department</td>
										<td class=" font-bold pr-1">Date: </td>
										<td class="">05/16/24</td>
										<td class=" font-bold pr-1">Date Needed: </td>
										<td class="">05/16/24</td>
									</tr>
									<tr>
										<td class="font-bold pr-1">End-Use:</td>
										<td class="">IT Department</td>
									</tr>
									<tr>
										<td class="font-bold pr-1">Purpose:</td>
										<td class="">IT Department</td>
									</tr>
								</table>
								<table class="table-bordered !text-xs mb-3" width="250%">
									<tr>
										<td class="bg-gray-50 " colspan="4"></td>
										<!-- loop vendors here start -->
										<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
											<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
											<!-- <p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
											<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
											<div class="flex justify-center space-x-2">
												<span>Mary Marie</span>
												<span>|</span>
												<span>(034) 9872-2772</span>
											</div>
										</td>
										<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
											<p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
											<!-- <p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
											<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
											<div class="flex justify-center space-x-2">
												<span>Mary Marie</span>
												<span>|</span>
												<span>(034) 9872-2772</span>
											</div>
										</td>
										<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
											<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
											<!--<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
											<p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>-->
											<div class="flex justify-center space-x-2"> 
												<span>Mary Marie</span>
												<span>|</span>
												<span>(034) 9872-2772</span>
											</div>
										</td>
										<!-- loop vendors here end-->
									</tr>
									<tr>
										<td class="uppercase bg-gray-100 p-1 align-top text-center" width="1%">#</td>
										<td class="uppercase bg-gray-100 p-1 align-top" width="10%">Item Description</td>
										<td class="uppercase bg-gray-100 p-1 align-top text-center" width="2%">Qty</td>
										<td class="uppercase bg-gray-100 p-1 align-top text-center" width="2%">UOM</td>
										<!-- loop offer header per vendor here start -->
										<td class="uppercase bg-gray-100 p-1 " width="10%">Offer</td>
										<td class="uppercase bg-gray-100 p-1 text-center"  width="3%">Unit Price</td>
										<td class="uppercase bg-gray-100 p-1 text-center" colspan="2" width="3%">Amount</td>
										<td class="uppercase bg-gray-100 p-1 text-center" width="5%">Comment</td>
										<!-- loop offer header per vendor here end-->
											<!-- loop offer header per vendor here start -->
										<td class="uppercase bg-gray-100 p-1 " width="10%">Offer</td>
										<td class="uppercase bg-gray-100 p-1 text-center"  width="3%">Unit Price</td>
										<td class="uppercase bg-gray-100 p-1 text-center" colspan="2" width="3%">Amount</td>
										<td class="uppercase bg-gray-100 p-1 text-center" width="5%">Comment</td>
										<!-- loop offer header per vendor here end-->
											<!-- loop offer header per vendor here start -->
										<td class="uppercase bg-gray-100 p-1 " width="10%">Offer</td>
										<td class="uppercase bg-gray-100 p-1 text-center"  width="3%">Unit Price</td>
										<td class="uppercase bg-gray-100 p-1 text-center" colspan="2" width="3%">Amount</td>
										<td class="uppercase bg-gray-100 p-1 text-center" width="5%">Comment</td>
										<!-- loop offer header per vendor here end-->
									</tr>
									<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
										<tr>
											<td class="p-1 align-top text-center" rowspan="4">1</td>
											<td class="p-1 align-top" rowspan="4">Monitor</td>
											<td class="p-1 align-top text-center" rowspan="4">5</td>
											<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
										</tr>
										<!-- loop here if 3 and below offers here -->
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
										<!-- loop here if 3 and below offers here -->
									<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

									<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
										<tr>
											<td class="p-1 align-top text-center" rowspan="4">2</td>
											<td class="p-1 align-top" rowspan="4">Mouse</td>
											<td class="p-1 align-top text-center" rowspan="4">5</td>
											<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
										</tr>
										<!-- loop here if 3 and below offers here -->
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>

													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>150.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>750.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
										<!-- loop here if 3 and below offers here -->
									<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

									<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
										<tr>
											<td class="p-1 align-top text-center" rowspan="4">3</td>
											<td class="p-1 align-top" rowspan="4">Keyboard</td>
											<td class="p-1 align-top text-center" rowspan="4">5</td>
											<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
										</tr>
										<!-- loop here if 3 and below offers here -->
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
											<tr>
												<!-- loop offers per vendor here -->
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
													<td class="p-1">
														sample 1
													</td>
													<td class="p-1 align-top bg-yellow-300">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>100.00</span>
														</div>
													</td>
													<td class="p-1 align-top" colspan="2">
														<div class="flex justify-between space-x-1">
															<span>Php</span>
															<span>500.00</span>
														</div>
													</td>
													
													<td class="p-1 align-top"></td>
												<!-- loop offers per vendor here -->
											</tr>
										<!-- loop here if 3 and below offers here -->
									<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
									<tr class="!border-0">
										<td class="!border-0" colspan="4"><br></td>
										<td class="!border-0" colspan="4"><br></td>
									</tr>
									<tr class="!border-0">
									<td class="!border-0 text-center">a.</td>
										<td class="!border-0 text-center" colspan="1">Price Validity</td>
										<td class="!border-0" colspan="2"></td>	
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan="4"></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan="4"></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan="4"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0 text-center">b.</td>
										<td class="!border-0 text-center" colspan="1">Payment Terms</td>
										<td class="!border-0" colspan="2"></td>	
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan=""></td>
										<td class="!border-0 text-right px-2" colspan="">Legend:</td>
										<td class="!border-0" colspan="2"></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan=""></td>
										<td class="!border-0 text-right px-2" colspan="">Legend:</td>
										<td class="!border-0" colspan="2"></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan=""></td>
										<td class="!border-0 text-right px-2" colspan="">Legend:</td>
										<td class="!border-0" colspan="2"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0 text-center">c.</td>
										<td class="!border-0 text-center" colspan="1">Delivery Term</td>
										<td class="!border-0" colspan="2"></td>	
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
										<td class="!border-0 bg-lime-500" width="1%"></td>
										<td class="!border-0" colspan=""></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
										<td class="!border-0 bg-lime-500" width="1%"></td>
										<td class="!border-0" colspan=""></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
										<td class="!border-0 bg-lime-500" width="1%"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0 text-center">d.</td>
										<td class="!border-0 text-center" colspan="1">Item's Warranty</td>
										<td class="!border-0" colspan="2"></td>	
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
										<td class="!border-0 bg-yellow-300" colspan="1"></td>
										<td class="!border-0" colspan=""></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
										<td class="!border-0 bg-yellow-300" colspan="1"></td>
										<td class="!border-0" colspan=""></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
										<td class="!border-0 bg-yellow-300" colspan="1"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0 text-center">e.</td>
										<td class="!border-0 text-center" colspan="1">In-land Freight</td>
										<td class="!border-0" colspan="2"></td>	
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan="4"></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan="4"></td>
										<td class="!border-0 !border-b" colspan="1"></td>
										<td class="!border-0" colspan="4"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0" colspan="4"><br></td>
										<td class="!border-0" colspan="4"><br></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0" colspan="4"><br></td>
										<td class="!border-0" colspan="4"><br></td>
									</tr>
								</table>
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
									<h5 class="leading-tight">You have successfully created an AOQ.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<a href="/pur_po/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed to PO</a>
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
									<h5 class="leading-tight">You have successfully saved an AOQ as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/pur_req/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>