<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const awesome =  ref();

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
</script>
<template>
	<navigation>
		<div class="row">
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
                    <span class="font-bold uppercase text-lg text-center text-yellow-500">CHANGE ORDER FORM</span>
					<hr class="border-dashed mt-">
					<div class="pt-1">
						<div>
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
                                        <span class="font-bold uppercase text-sm text-gray-500">Current Data</span>
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
													<td class="p-1 text-right ">200.00</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
													<td class="p-1 text-right ">200.00</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
													<td class="p-0">
														<div class="flex">
															<input type="text" class="w-10 bg-white border-r text-center" disabled value="">
															<input type="text" class="w-full bg-white p-1 text-right" disabled value="">
														</div>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
													<td class="p-1 text-right ">100.00</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">1000.00</td>
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
													<td class="uppercase p-1" colspan="2">Description</td>
													<td class="uppercase p-1 text-center" width="12%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="12%">Total</td>
												</tr>
												<tr class="">
													<td class="align-top  p-1 text-center">1</td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="5">
                                                    </td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="pc">
                                                    </td>
													<td class="align-top leading-none p-0" colspan="2">
                                                        <textarea type="text" class="w-full p-1 leading-tight resize p-1 text-left" name="" id="" value="Monitor" rows="1"></textarea>
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="100.00">
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="500.00">
                                                    </td>
												</tr>
												<tr class="">
													<td class="align-top  p-1 text-center">2</td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="5">
                                                    </td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="pc">
                                                    </td>
													<td class="align-top leading-none p-0" colspan="2">
                                                        <textarea type="text" class="w-full p-1 leading-tight resize p-1 text-left" name="" id="" value="Mouse" rows="1"></textarea>
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="100.00">
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="500.00">
                                                    </td>
												</tr>
                                                <tr class="">
													<td class="align-top  p-1 text-center">3</td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="5">
                                                    </td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="pc">
                                                    </td>
													<td class="align-top leading-none p-0" colspan="2">
                                                        <textarea type="text" class="w-full p-1 leading-tight resize p-1 text-left" name="" id="" value="KeyBoard" rows="1"></textarea>
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="100.00">
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="500.00">
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
												</tr>
												<tr class="">
													<td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="5">
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>PR-19772-8727</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
													</td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-0.5 text-right pr-1"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
													<td class="p-0">
														<div class="flex">
															<input type="text" class="w-10 bg-yellow-50 border-r text-center" placeholder="%">
															<input type="text" class="w-full bg-yellow-50 p-1 text-right">
														</div>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">2000.00</td>
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
													<input type="text" class="p-1 w-full bg-yellow-50">
												</td>
												<td class="p-0" width="1">
													<button class="btn btn-primary p-1">
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
														<span class="w-32">Delivery Time </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr>
												<td class="px-1" colspan="2">sample terms and condition</td>
												<td class="p-0 align-top" width="1">
													<button class="btn btn-danger p-1">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
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
													<input type="text" class="p-1 w-full bg-yellow-50">
												</td>
												<td class="p-0" width="1">
													<button class="btn btn-primary p-1">
														<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
													</button>
												</td>
											</tr>
											<tr>
												<td class="px-1" colspan="2">sample instructions</td>
												<td class="p-0 align-top" width="1">
													<button class="btn btn-danger p-1">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
											<tr>
												<td class="px-1" colspan="2">sample instructions -Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</td>
												<td class="p-0 align-top" width="1">
													<button class="btn btn-danger p-1">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<hr	class="border-dashed mt-4">
								<div class=""></div>
								<div class="row my-2 bg-yellow-50 px-2 py-3" v-if="awesome"> 
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
										<a href="/pur_po/view" class="btn btn-primary btn-sm" >Approve</a>
									</div>
								</div>
								
								<div class="row my-2" v-else> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-center space-x-2">
											<div class="flex justify-between space-x-1">
												<button type="submit" class="btn btn-warning w-26 !text-white" >Save as Draft</button>
												<button  class="btn btn-primary w-36"  @click="awesome = !awesome">Save</button>
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