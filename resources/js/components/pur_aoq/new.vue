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

	const pr_det = ref(false)
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Abstract of Quotation <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_aoq">Abstract of Quotation</a></li>
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
								<label class="text-gray-500 m-0" for="">Choose RFQ Number</label>
								<input type="file" name="img[]" class="file-upload-default">
								<div class="input-group col-xs-12">
									<select class="form-control file-upload-info">
                                        <option value="">RFQ-CENPRI-1001</option>
                                    </select>
									<span class="input-group-append">
										<button class="btn btn-primary" type="button" @click="pr_det = !pr_det">Select</button>
									</span>
								</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div v-show="pr_det">
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
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mb-3">
										<tr class="bg-gray-50">
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="40%">Vendors</td>
										</tr>
										<tr>
											<td class="p-1 text-center">5</td>
											<td class="p-1 text-center">pc/s</td>
											<td class="p-1">PN-0991-001</td>
											<td class="p-1">Monitor</td>
											<td class="p-1" rowspan="3">
												<ul class="m-0 !text-xs !list-disc">
													<li>Lectrix Solutions Electrical Supplies & Services Cebu</li>
													<li>MF Computer Solutions, Inc.</li>
													<li>Nexus Industrial Prime Solutions Corp.</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td class="p-1 text-center">5</td>
											<td class="p-1 text-center">pc/s</td>
											<td class="p-1">PN-0991-222</td>
											<td class="p-1">Mouse</td>
										</tr>
										<tr>
											<td class="p-1 text-center">5</td>
											<td class="p-1 text-center">pc/s</td>
											<td class="p-1">PN-0991-333</td>
											<td class="p-1">Keyboard</td>
										</tr>
									</table>
								</div>
							</div>
						
							<br>
							<div class="row my-2"> 
								<div class="col-lg-12 col-md-12">
									<div class="flex justify-center space-x-2">
										<a href="/pur_aoq/print_te" class="btn btn-primary mr-2 w-44">Print for TE</a>
										<a href="/pur_aoq/new_second" class="btn btn-primary mr-2 w-44">Proceed to Award</a>
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