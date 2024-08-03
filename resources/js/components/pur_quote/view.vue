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
									<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
									<table class="table-borddered w-full !text-xs mb-2">
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
									<!-- <div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div> -->
								</div>
							</div>
						</div>
                        
						<br>
						<!-- <hr class="border-dashed"> -->
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <button type="submit" class="btn btn-primary mr-2 w-44">Print</button> -->
									<button type="submit" class="btn btn-primary mr-2 w-44">Canvass Complete</button>
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