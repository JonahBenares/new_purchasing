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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Abstract of Quotation <small>New</small></h3>
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
								<label class="text-gray-500 m-0" for="">Choose PR and RFQ Number</label>
								<input type="file" name="img[]" class="file-upload-default">
								<div class="input-group col-xs-12">
									<select class="form-control file-upload-info">
                                        <option value="">-Select PR-</option>
                                        <option value="">PR-CENPRI-1001</option>
                                    </select>
									<select class="form-control file-upload-info">
										<option value="">-Select RFQ-</option>
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
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">PR No: </span>
									<span class="text-sm text-gray-700">PR-CENPRI24-1002</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">AOQ No: </span>
									<span class="text-sm text-gray-700">AOQ-1009-1001</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">05/16/24</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">IT Department</span>
								</div>
								
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">RFQ No: </span>
									<span class="text-sm text-gray-700">RFQ-988-2882</span>
								</div>
								
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use:</span>
									<span class="text-sm text-gray-700">February 16, 2024</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Requested By: </span>
									<span class="text-sm text-gray-700">Henne Tanan</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">Replace damage monitor, mouse and keyboard</span>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered text-sm" >
										<tr class="bg-gray-100">
											<td class="p-1" width="2%"><input type="checkbox" name="check"></td>
											<td class="p-1">Vendor</td>
										</tr>
										<tr >
											<td class="p-1"><input type="checkbox" name="check"></td>
											<td class="p-1">Vendor</td>
										</tr>
									</table>
								</div>
							</div>
							<br>
							<hr>
							<div class="modal_s_items">
								<div class="row">
									<div class="col-lg-4 col-md-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Date Needed</label>
											<input type="date" class="form-control" placeholder="" >
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Prepared by</label>
											<select class="form-control" placeholder="" >
												<option value="">--Select Employee--</option>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Received and Checked by</label>
											<select class="form-control" placeholder="" >
												<option value="">--Select Employee--</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Award Recommended by</label>
											<select class="form-control" placeholder="" >
												<option value="">--Select Employee--</option>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Recommending Approval</label>
											<select class="form-control" placeholder="" >
												<option value="">--Select Employee--</option>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Aprroved by</label>
											<select class="form-control" placeholder="" >
												<option value="">--Select Employee--</option>
											</select>
										</div>
									</div>
								</div>
								<hr>
							</div> 
						
							<br>
							<div class="row my-2"> 
								<div class="col-lg-12 col-md-12">
									<div class="flex justify-center space-x-2">
										<a href="/pur_aoq/print_te" class="btn btn-primary mr-2 w-44">Create AOQ</a>
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