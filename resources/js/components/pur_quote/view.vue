<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
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
</script>
<template>
	<navigation>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<span class="pt-2">
						<h3 class="card-title !text-lg m-0">Request for Quotation <small>View</small></h3>
					</span>
					<div class="pt-3">
						<hr class="border-dashed mt-2">
						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">RFQ Number: </span>
								<span class="text-sm text-gray-700">RFQ-19872-1881</span>
							</div>
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Date:</span>
								<span class="text-sm text-gray-700">December 16, 1990</span>
							</div>
						</div>
						<br>
						<div class="w-full flex justify-between space-x-1">
							<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Technosure Electronics and Electrical Supply and Services</button>
							<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Inter-Continental Systems Integrators Incorporated (ICSII) - Bacolod</button>
							<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button>
							<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
							<button class="btn btn-primary p-1">
								<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
							</button>
						</div>
						<div class="bg-gray-200 !border !border-orange-200">
							<div class="page">
								<div class="subpage">
									<div class="border w-full text-center p-4 bg-blue-100"> Header here</div>
									<table class="table-bordered w-full !text-xs mb-2">
										<tr>
											<td class="" width="10%">Date: </td>
											<td class="">December 28, 2023</td>
										</tr>
										<tr>
											<td class="">PR No:</td>
											<td class="" width="55%">PR-100812-928</td>
											<td class="" width="8%">RFQ No.:</td>
											<td class="" >202312-3857-B-CNPR</td>
											<td class="">Urg:</td>		    			
											<td class="">X</td>
										</tr>
										<tr>
											<td class="">Supplier: </td>
											<td class="">Bearing Center &amp; Machinery Inc. </td>
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
											<td class="p-1 align-top text-center">23</td>
											<td class="p-1 align-top">Angular Contact Ball Bearing 7305</td>
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
											<td class="p-1 align-top text-center">23</td>
											<td class="p-1 align-top">Angular Contact Ball Bearing 7305</td>
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
											<td class="p-1 align-top text-center">23</td>
											<td class="p-1 align-top">Angular Contact Ball Bearing 7305</td>
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
									<div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div>
								</div>
							</div>
						</div>
                        
						<br>
						<!-- <hr class="border-dashed"> -->
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-primary mr-2 w-44">Print</button>
									<button type="submit" class="btn btn-primary mr-2 w-44">Canvass Complete</button>
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
									<label class="text-gray-500 m-0" for="">RFQ Name</label>
									<input type="text" class="form-control" placeholder="RFQ Name">
								</div>
							</div>
							<div class="col-lg-6 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">RFQ Series</label>
									<input type="text" class="form-control" placeholder="RFQ Series">
								</div>
							</div>
							<div class="col-lg-6 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Date Created</label>
									<input type="text" class="form-control" placeholder="Date Created"  onfocus="(this.type='date')">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<table class="table-bordered !w-full mt-3"  >
									<tr>
										<td class="p-1 px-2 text-sm">Add Vendor</td>
										<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
											<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
										</td>
									</tr>
									<tr>
										<td class=""><input placeholder="Vendor" type="text" class="w-full text-sm p-1 px-2"></td>
										<td class="text-center">
											<button class="btn btn-primary p-1">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
									<tr>
										<td class=""><input placeholder="Vendor" type="text" class="w-full text-sm p-1 px-2"></td>
										<td class="text-center">
											<button class="btn btn-primary p-1">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>

									<tr>
										<td class=""><input placeholder="Vendor" type="text" class="w-full text-sm p-1 px-2"></td>
										<td class="text-center">
											<button class="btn btn-primary p-1">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
									<tr>
										<td class=""><input placeholder="Vendor" type="text" class="w-full text-sm p-1 px-2"></td>
										<td class="text-center">
											<button class="btn btn-primary p-1">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" @click="openModel()" class="btn btn-info mr-2 w-44">Create New RFQ</button>
									<button type="submit" @click="openModel()" class="btn btn-primary mr-2 w-44">Save and Proceed</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>