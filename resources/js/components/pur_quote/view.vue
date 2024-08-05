<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"

	const vendor =  ref();
	const showModal = ref(false)
	const hideModal = ref(true)
	const openModel = () => {
		showModal.value = !showModal.value
	}
	const closeModal = () => {
		showModal.value = !hideModal.value
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Request for Quotation <small>Print</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_quote">Request for Quotation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					
					<hr class="border-dashed mt-2">
					<div class="pt-1">
						<div class="row">
							<div class="col-lg-4">
								<span class="text-sm text-gray-700 font-bold pr-1">RFQ No: </span>
								<span class="text-sm text-gray-700">RFQ-CENPRI-1001</span>
							</div>
							<div class="col-lg-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Date:</span>
								<span class="text-sm text-gray-700">February 16, 2024</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">RFQ Name: </span>
								<span class="text-sm text-gray-700">rfq_it001</span>
							</div>
							
						</div>
						<br>
						<div v-if="vendor === 'vendor_1'">
							<div class="w-full flex justify-between space-x-1">
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
								<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
								<button @click="openModel()" class="btn btn-primary p-1">
									<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
								</button>
							</div>
							<div class="bg-gray-200 !border !border-orange-200">
								<div class="page">
									<div class="subpage">
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
										<table class="table-bsordered w-full !text-xs mb-2">
											<tr>
												<td class="" width="10%">Date: </td>
												<td class="">December 28, 2023</td>
												<td class="" width="8%">RFQ No.:</td>
												<td class="" >RFQ-CENPRI-1001</td>
												<td class="">Urg:</td>		    			
												<td class="">X</td>
											</tr>
											<tr>
												<td class="">PR No:</td>
												<td class="" width="50%">PR-CENPRI24-1002</td>
												<td class="" width="10%">RFQ Name:</td>
												<td class="" colspan="3">rfq_it001</td>
											</tr>
											<tr>
												<td class="">Supplier: </td>
												<td class="">MF Computer Solutions, Inc.</td>
												<td class="">Tel. No.:</td>
												<td class="" colspan="3">(034) 433-8370</td>
											</tr>
										</table>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
												</td>
											</tr>
										</table>
										<div class="border w-full text-center p-4 bg-green-100"> Conditions here</div>
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div> -->
									</div>
								</div>
							</div>
						</div>
						<div v-else-if="vendor === 'vendor_2'">
							<div class="w-full flex justify-between space-x-1">
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
								<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
								<button @click="openModel()" class="btn btn-primary p-1">
									<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
								</button>
							</div>
							<div class="bg-gray-200 !border !border-orange-200">
								<div class="page">
									<div class="subpage">
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
										<table class="table-bsordered w-full !text-xs mb-2">
											<tr>
												<td class="" width="10%">Date: </td>
												<td class="">December 28, 2023</td>
												<td class="" width="8%">RFQ No.:</td>
												<td class="" >RFQ-CENPRI-1001</td>
												<td class="">Urg:</td>		    			
												<td class="">X</td>
											</tr>
											<tr>
												<td class="">PR No:</td>
												<td class="" width="50%">PR-CENPRI24-1002</td>
												<td class="" width="10%">RFQ Name:</td>
												<td class="" colspan="3">rfq_it001</td>
											</tr>
											<tr>
												<td class="">Supplier: </td>
												<td class="">Nexus Industrial Prime Solutions Corp.</td>
												<td class="">Tel. No.:</td>
												<td class="" colspan="3">(034) 433-8370</td>
											</tr>
										</table>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
												</td>
											</tr>
										</table>
										<div class="border w-full text-center p-4 bg-green-100"> Conditions here</div>
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div> -->
									</div>
								</div>
							</div>
						</div>
						<div v-else>
							<div class="w-full flex justify-between space-x-1">
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
								<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
								<button @click="openModel()" class="btn btn-primary p-1">
									<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
								</button>
							</div>
							<div class="bg-gray-200 !border !border-orange-200">
								<div class="page">
									<div class="subpage">
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
										<table class="table-bsordered w-full !text-xs mb-2">
											<tr>
												<td class="" width="10%">Date: </td>
												<td class="">December 28, 2023</td>
												<td class="" width="8%">RFQ No.:</td>
												<td class="" >RFQ-CENPRI-1001</td>
												<td class="">Urg:</td>		    			
												<td class="">X</td>
											</tr>
											<tr>
												<td class="">PR No:</td>
												<td class="" width="50%">PR-CENPRI24-1002</td>
												<td class="" width="10%">RFQ Name:</td>
												<td class="" colspan="3">rfq_it001</td>
											</tr>
											<tr>
												<td class="">Supplier: </td>
												<td class="">Lectrix Solutions Electrical Supplies & Services Cebu </td>
												<td class="">Tel. No.:</td>
												<td class="" colspan="3">(034) 433-8370</td>
											</tr>
										</table>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
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
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
													<textarea type="text" class="border-b p-1 w-full h-14 !align-top text-center"></textarea>
												</td>
											</tr>
										</table>
										<div class="border w-full text-center p-4 bg-green-100"> Conditions here</div>
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div> -->
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-primary mr-2 w-44">Canvass Complete</button>
									<!-- <button type="submit" class="btn btn-primary mr-2 w-44">Canvass Complete</button> -->
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
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Vendor</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
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
	</navigation>
</template>