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
						<h3 class="card-title !text-lg m-0">Request for Quotation <small>New</small></h3>
					</span>
					<div class="pt-3">
						<hr class="border-dashed mt-0">
						<div class="row">							
							<div class="col-lg-6 offset-lg-3 col-md-3">
								<div class="form-group">
								<label class="text-gray-500 m-0" for="">Choose PR Number</label>
								<input type="file" name="img[]" class="file-upload-default">
								<div class="input-group col-xs-12">
									<select class="form-control file-upload-info">
                                        <option value="">PR-1982-19871</option>
                                        <option value="">PR-1982-10019</option>
                                        <option value="">PR-1982-10992</option>
                                    </select>
									<span class="input-group-append">
										<button class="btn btn-primary" type="button">Select</button>
									</span>
								</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">PR Number: </span>
								<span class="text-sm text-gray-700">PR-19872-1881</span>
							</div>
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Date:</span>
								<span class="text-sm text-gray-700">December 16, 1990</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Supplier:</span>
								<span class="text-sm text-gray-700">Jlee Marketing</span>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mb-3">
									<tr class="bg-gray-50">
										<td class="p-1 uppercase text-center" width="5%">
                                            <input type="checkbox">
                                        </td>
										<td class="p-1 uppercase text-center" width="7%">Qty</td>
										<td class="p-1 uppercase text-center" width="7%">UOM</td>
										<td class="p-1 uppercase" width="20%">PN No.</td>
										<td class="p-1 uppercase" width="">Item Description</td>
										<td class="p-1 uppercase" width="10%">WH Stocks</td>
										<td class="p-1 uppercase" width="15%">Date Needed</td>
									</tr>
									<tr>
										<td class="p-1 text-center">
                                            <input type="checkbox">
                                        </td>
										<td class="p-1 text-center">20</td>
										<td class="p-1 text-center">Kg</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
									</tr>
									<tr>
										<td class="p-1 text-center">
                                            <input type="checkbox">
                                        </td>
										<td class="p-1 text-center">20</td>
										<td class="p-1 text-center">Kg</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
									</tr>
									<tr>
										<td class="p-1 text-center">
                                            <input type="checkbox">
                                        </td>
										<td class="p-1 text-center">20</td>
										<td class="p-1 text-center">Kg</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
									</tr>
									<tr>
										<td class="p-1 text-center">
                                            <input type="checkbox">
                                        </td>
										<td class="p-1 text-center">20</td>
										<td class="p-1 text-center">Kg</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
										<td class="p-1">sample</td>
									</tr>
								</table>
							</div>
						</div>
                        
						<br>
						<!-- <hr class="border-dashed"> -->
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" @click="openModel()" class="btn btn-primary mr-2 w-44">Add Vendor</button>
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
									<a href="/pur_quote/print" class="btn btn-primary mr-2 w-44">Save and Proceed</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>