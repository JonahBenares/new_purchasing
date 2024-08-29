<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, XMarkIcon, PencilIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const modalNew = ref(false)
    const modalEdit = ref(false)
	const hideModal = ref(true)
	const openNew = () => {
		modalNew.value = !modalNew.value
	}
    const openEdit = () => {
		modalEdit.value = !modalEdit.value
	}
	const closeModal = () => {
		modalNew.value = !hideModal.value
		modalEdit.value = !hideModal.value
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Vendor <small>Update</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/vendor">Vendor List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<div class="pt-2">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Vendor Name</label>
									<textarea class="form-control" placeholder="Vendor Name"></textarea>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Products/Services</label>
									<textarea class="form-control" placeholder="Products/Services"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="flex justify-between  mt-2 mb-3  z-50 ">
									<button @click="openNew()" class="btn btn-primary mt-2 mt-xl-0 text-white">
										<span>Add Branch</span>
									</button>
								</div>
								<div class="overflow-x-scroll">
									<table class="border table-bordered" width="200%">
										<tr>
											<th class="!text-xs p-1 bg-gray-100 uppercase" width="15%"> Address</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase" width="15%"> Terms</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase" width="8%"> Phone</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase" width="8%"> Fax</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase" width="10%"> Contact Person</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase" width="10%"> Email</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase"> TIN</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase"> Type</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase"> EWT</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase"> Vat</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase"> Status</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase"> Notes</th>
											<th class="!text-xs p-1 bg-gray-100 uppercase" width="1%"> 
												<span class="text-center justify-center flex px-auto">
													<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
												</span>
											</th>
										</tr>
										<tr>
											<td class="!text-sm p-1">Lacson Street, Bacolod City</td>
											<td class="!text-sm p-1">Terms</td>
											<td class="!text-sm p-1">09225488794</td>
											<td class="!text-sm p-1">(034) 45454488</td>
											<td class="!text-sm p-1">Chenne Lyn Marie</td>
											<td class="!text-sm p-1">clm@gmail.com</td>
											<td class="!text-sm p-1">55845-5585-545585</td>
											<td class="!text-sm p-1">Type</td>
											<td class="!text-sm p-1">EWT%</td>
											<td class="!text-sm p-1">Activr</td>
											<td class="!text-sm p-1">5%</td>
											<td class="!text-sm p-1">Notes</td>
											<td class="!text-sm p-0" align="center">
												<button class="btn-info btn btn-xs text-white p-1" @click="openEdit()">
													<PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3"></PencilIcon>
												</button>
											</td>
										</tr>
										<tr>
											<td class="!text-sm p-1">Lacson Street, Bacolod City</td>
											<td class="!text-sm p-1">Terms</td>
											<td class="!text-sm p-1">09225488794</td>
											<td class="!text-sm p-1">(034) 45454488</td>
											<td class="!text-sm p-1">Chenne Lyn Marie</td>
											<td class="!text-sm p-1">clm@gmail.com</td>
											<td class="!text-sm p-1">55845-5585-545585</td>
											<td class="!text-sm p-1">Type</td>
											<td class="!text-sm p-1">EWT%</td>
											<td class="!text-sm p-1">Activr</td>
											<td class="!text-sm p-1">5%</td>
											<td class="!text-sm p-1">Notes</td>
											<td class="!text-sm p-0" align="center">
												<button class="btn-info btn btn-xs text-white p-1" @click="openEdit()">
													<PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3"></PencilIcon>
												</button>
											</td>
										</tr>
										<tr>
											<td class="!text-sm p-1">Lacson Street, Bacolod City</td>
											<td class="!text-sm p-1">Terms</td>
											<td class="!text-sm p-1">09225488794</td>
											<td class="!text-sm p-1">(034) 45454488</td>
											<td class="!text-sm p-1">Chenne Lyn Marie</td>
											<td class="!text-sm p-1">clm@gmail.com</td>
											<td class="!text-sm p-1">55845-5585-545585</td>
											<td class="!text-sm p-1">Type</td>
											<td class="!text-sm p-1">EWT%</td>
											<td class="!text-sm p-1">Activr</td>
											<td class="!text-sm p-1">5%</td>
											<td class="!text-sm p-1">Notes</td>
											<td class="!text-sm p-0" align="center">
												<button class="btn-info btn btn-xs text-white p-1" @click="openEdit()">
													<PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3"></PencilIcon>
												</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-info mr-2 w-44">Update</button>
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
			<div class="modal pt-4 px-3 " :class="{ show:modalNew }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Branch</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Address</label>
									<textarea class="form-control" placeholder="Address"></textarea>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Terms</label>
									<textarea class="form-control" placeholder="Terms"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Phone Number</label>
									<input type="text" class="form-control" placeholder="Phone Number">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Fax Number</label>
									<input type="text" class="form-control" placeholder="Fax Number">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Contact Person</label>
									<input type="text" class="form-control" placeholder="Contact Person">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Email</label>
									<input type="email" class="form-control" placeholder="Email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >TIN</label>
									<input type="text" class="form-control" placeholder="TIN">
								</div>
							</div>
							<div class="col-lg-6  col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Type</label>
									<input type="text" class="form-control" placeholder="Type">
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >EWT%</label>
									<input type="text" class="form-control" placeholder="EWT%">
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Status</label>
									<select class="form-control">
										<option value="">Inactive</option>
										<option value="">Active</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="flex !justify-center mt-4 space-x-4">
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >VAT</label>
										<input type="radio" class="form-control !w-5 !h-5" placeholder="VAT">
									</div>
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >Non-VAT</label>
										<input type="radio" class="form-control !w-5 !h-5" placeholder="VAT">
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Notes</label>
									<textarea class="form-control" placeholder="Notes"></textarea>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/" class="btn btn-primary mr-2 w-44">Save</a>
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
			<div class="modal pt-4 px-3 " :class="{ show:modalEdit }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Update Branch</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Address</label>
									<textarea class="form-control" placeholder="Address"></textarea>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Terms</label>
									<textarea class="form-control" placeholder="Terms"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Phone Number</label>
									<input type="text" class="form-control" placeholder="Phone Number">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Fax Number</label>
									<input type="text" class="form-control" placeholder="Fax Number">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Contact Person</label>
									<input type="text" class="form-control" placeholder="Contact Person">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Email</label>
									<input type="email" class="form-control" placeholder="Email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >TIN</label>
									<input type="text" class="form-control" placeholder="TIN">
								</div>
							</div>
							<div class="col-lg-6  col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Type</label>
									<input type="text" class="form-control" placeholder="Type">
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >EWT%</label>
									<input type="text" class="form-control" placeholder="EWT%">
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Status</label>
									<select class="form-control">
										<option value="">Inactive</option>
										<option value="">Active</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="flex !justify-center mt-4 space-x-4">
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >VAT</label>
										<input type="radio" class="form-control !w-5 !h-5" placeholder="VAT">
									</div>
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >Non-VAT</label>
										<input type="radio" class="form-control !w-5 !h-5" placeholder="VAT">
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Notes</label>
									<textarea class="form-control" placeholder="Notes"></textarea>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/" class="btn btn-info mr-2 w-44">Update</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>