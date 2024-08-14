<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"

	const po_det = ref(false)
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Delivery Receipt <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_dr">Delivery Receipt</a></li>
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
					<hr class="border-dashed mt-0">
					<div class="pt-1">
						<div class="row">							
							<div class="col-lg-6 offset-lg-3 col-md-3">
								<div class="form-group">
								<label class="text-gray-500 m-0" for="">Choose PO Number</label>
								<div class="input-group col-xs-12">
									<select class="form-control file-upload-info">
										<option value="">Select PO</option>
										<option value="">PO-1992-991</option>
									</select>
									<span class="input-group-append">
										<button class="btn btn-primary" type="button" @click="po_det = !po_det">Select</button>
									</span>
								</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div v-show="po_det">
							<div class="row">
								<div class="col-lg-8">
									<span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
									<span class="text-sm text-gray-700">PO-CENPRI24-1001</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">DR No: </span>
									<span class="text-sm text-gray-700">DR-CENPRI24-1001</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8">
									<span class="text-sm text-gray-700 font-bold pr-1">PR No: </span>
									<span class="text-sm text-gray-700">PR-CENPRI24-1002</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">05/16/24</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8">
									<span class="text-sm text-gray-700 font-bold pr-1">End-use: </span>
									<span class="text-sm text-gray-700">IT Department</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Requestor: </span>
									<span class="text-sm text-gray-700">Henne Marie Tanan</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose:</span>
									<span class="text-sm text-gray-700">Replace damage monitor, mouse and keyboard</span>
								</div>
							</div>
														
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mt-3">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="2%">#</td>
											<td class="p-1 uppercase text-center" width="25%">Supplier</td>
											<td class="p-1 uppercase text-center" width="25%">Description</td>
											<td class="p-1 uppercase text-center" width="7%">To Deliver</td>
											<td class="p-1 uppercase text-center" width="8%">DLVRD Qty</td>
											<td class="p-1 uppercase text-center" width="5%">Received</td>
											<td class="p-1 uppercase" width="5%">UOM</td>
											<td class="p-1 uppercase" width="5%">Remarks</td>
										</tr>
										<tr>
											<td class="p-1 text-center">1</td>
											<td class="p-1 ">MF Computer Solutions, Inc.</td>
											<td class="p-1 ">Monitor</td>
											<td class="p-0"><input type="text" class="w-full p-1 bg-orange-50 text-center" placeholder="5"></td>
											<td class="p-1 text-center"></td>
											<td class="p-1"></td>
											<td class="p-1"></td>
											<td class="p-1">remarks</td>
										</tr>
										<tr>
											<td class="p-1 text-center">2</td>
											<td class="p-1 ">MF Computer Solutions, Inc.</td>
											<td class="p-1 ">Mouse</td>
											<td class="p-0"><input type="text" class="w-full p-1 bg-orange-50 text-center" placeholder="5"></td>
											<td class="p-1 text-center"></td>
											<td class="p-1"></td>
											<td class="p-1"></td>
											<td class="p-1">remarks</td>
										</tr>
										<tr>
											<td class="p-1 text-center">3</td>
											<td class="p-1 ">MF Computer Solutions, Inc.</td>
											<td class="p-1 ">Keyboard</td>
											<td class="p-0"><input type="text" class="w-full p-1 bg-orange-50 text-center" placeholder="5"></td>
											<td class="p-1 text-center"></td>
											<td class="p-1"></td>
											<td class="p-1"></td>
											<td class="p-1">remarks</td>
										</tr>
									</table>
								</div>
							</div>
							<br>
							<!-- <hr class="border-dashed"> -->
							<div class="row my-2"> 
								<div class="col-lg-12 col-md-12">
									<div class="flex justify-center space-x-2">
										<button type="submit" class="btn btn-primary mr-2 w-36">Save & Print</button>
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