<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, XMarkIcon, PlusIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import {onMounted, ref} from "vue";
	import { useRouter } from "vue-router";
	const router = useRouter()
    let branches=ref([]);
    let vendor_name=ref('');
    let product_services=ref('');
    let address=ref('');
    let identifier=ref('');
    let terms=ref('');
    let phone=ref('');
    let fax=ref('');
    let contact_person=ref('');
    let email=ref('');
    let tin=ref('');
    let type=ref('');
    let notes=ref('');
    let ewt=ref(0);
    let vat=ref(0);
    let status=ref('Active');
    let remove_id=ref(0);

	const modalNew = ref(false)
    const modalEdit = ref(false)
	const successAlert = ref(false)
	const successBranchAlert = ref(false)
	const VendorHeadAlert = ref(false)
	const AddBranchAlert = ref(false)
	const BranchAddressAlert = ref(false)
    const RemoveAlert = ref(false)
	const termsModal = ref(false)
    const viewTermsModal = ref(false)
	const hideModal = ref(true)

	const openNew = () => {
		modalNew.value = !modalNew.value
	}

	const openTerms = () => {
		termsModal.value = !termsModal.value
	}
	const openViewTerms = () => {
		viewTermsModal.value = !viewTermsModal.value
	}
	const closeModal = () => {
		viewTermsModal.value = !hideModal.value
		modalNew.value = !hideModal.value
		termsModal.value = !hideModal.value
		modalNew.value = !hideModal.value
		modalEdit.value = !hideModal.value
		RemoveAlert.value = !hideModal.value
		successAlert.value = !hideModal.value
		successBranchAlert.value = !hideModal.value
		VendorHeadAlert.value = !hideModal.value
		AddBranchAlert.value = !hideModal.value
		BranchAddressAlert.value = !hideModal.value
	}

	const RemoveModal = (index) => {
		RemoveAlert.value = !RemoveAlert.value
		remove_id.value = index
	}

	const removebranch= (index) =>{
		branches.value.splice(index,1)
		if(index>=1){
			document.getElementById("SubmitButton").disabled = false;
		}else{
			document.getElementById("SubmitButton").disabled = true;
		}
		closeModal()
	}

	const ShowList = () => {
		router.push('/vendor/')
	}

	const CreateNewVendor = () => {
		closeModal()
		vendor_name.value=''
		product_services.value=''
		branches.value=''
	}

	const UpdateAddress = () => {
		BranchAddressAlert.value = !hideModal.value
	}

	const AddBranch = () => {
		successBranchAlert.value = !hideModal.value
		address.value=''
		identifier.value=''
		terms.value=''
		phone.value=''
		fax.value=''
		contact_person.value=''
		email.value=''
		tin.value=''
		type.value=''
		notes.value=''
		ewt.value=0
		vat.value=0
		status.value='Active'
	}

	const AddNewBranch= () => {
		if(address.value == ''){
			BranchAddressAlert.value = !BranchAddressAlert.value
		}else{
			successBranchAlert.value = !successBranchAlert.value
					branches.value.push({
					address:address.value,
					identifier:identifier.value,
					terms:terms.value,
					phone:phone.value,
					fax:fax.value,
					contact_person:contact_person.value,
					email:email.value,
					tin:tin.value,
					type:type.value,
					notes:notes.value,
					ewt:ewt.value,
					vat:vat.value,
					status:status.value,
					});
				if(branches.value.length>=1){
					document.getElementById("SubmitButton").disabled = false;
				}
				address.value=''
				identifier.value=''
				terms.value=''
				phone.value=''
				fax.value=''
				contact_person.value=''
				email.value=''
				tin.value=''
				type.value=''
				notes.value=''
				ewt.value=0
				vat.value=0
				status.value='Active'
		}
	}

	const SaveNewVendor = () => {
		// if(confirm("Are you sure you want to save this Vendor?")){
		if(vendor_name.value != ''){
			if(branches.value.length>=1){
				const formVendor= new FormData()
					formVendor.append('vendor_name',vendor_name.value)
					formVendor.append('product_services',product_services.value)
					formVendor.append('vendor_branches', JSON.stringify(branches.value))
					axios.post("/api/add_vendor/", formVendor).then(function () {
						// form.value.company_name=''
						successAlert.value = !successAlert.value
					});
			}else{
				AddBranchAlert.value = !AddBranchAlert.value
			}
		}else{
			VendorHeadAlert.value = !VendorHeadAlert.value
		}
		// }
	}

	const VendorChecker = async () => {
		let response = await axios.get("/api/check_vendor_name/"+vendor_name.value);
			if(response.data.count_vendor != 0){
				VendorHeadAlert.value = !VendorHeadAlert.value
			}
	}

	const BranchChecker = async () => {
	for (var i = 0; i < branches.value.length; i++) {
			if(branches.value[i].address == address.value || address.value == ''){
				BranchAddressAlert.value = !BranchAddressAlert.value
			}
		}
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Vendor <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/vendor">Vendor List</a></li>
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
					<div class="pt-2">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Vendor Name</label>
									<textarea class="form-control" placeholder="Vendor Name" v-model="vendor_name" @change="VendorChecker()"></textarea>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Products/Services</label>
									<textarea class="form-control" placeholder="Products/Services" v-model="product_services"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="flex justify-start  mt-2 mb-3  z-50 space-x-2">
									<button @click="openNew()" class="btn btn-primary btn-sm mt-2 mt-xl-0 text-white">
										<span>Add Branch</span>
									</button>
									<button @click="openTerms()" class="btn btn-primary btn-sm btn-sm mt-2 mt-xl-0 text-white">
										<span>Add Terms</span>
									</button>
								</div>
								<div class="overflow-x-scroll">
									<table class="border table-bordered" width="180%">
										<tr>
											<th class="!text-xs p-1 bg-gray-100" width="15%"> Address</th>
											<th class="!text-xs p-1 bg-gray-100" width="10%"> Identifier</th>
											<th class="!text-xs p-1 bg-gray-100" width="12%"> Terms</th>
											<th class="!text-xs p-1 bg-gray-100" width="8%"> Phone</th>
											<th class="!text-xs p-1 bg-gray-100" width="8%"> Fax</th>
											<th class="!text-xs p-1 bg-gray-100" width="8%"> Contact Person</th>
											<th class="!text-xs p-1 bg-gray-100" width="10%"> Email</th>
											<th class="!text-xs p-1 bg-gray-100" width="10%"> TIN</th>
											<th class="!text-xs p-1 bg-gray-100"> Type</th>
											<th class="!text-xs p-1 bg-gray-100"> EWT</th>
											<th class="!text-xs p-1 bg-gray-100"> Vat</th>
											<th class="!text-xs p-1 bg-gray-100"> Status</th>
											<th class="!text-xs p-1 bg-gray-100" width="5%"> Notes</th>
											<th class="!text-xs p-1 bg-gray-100" width="1%"> 
												<span class="text-center justify-center flex px-auto">
													<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
												</span>
											</th>
										</tr>
										<tr v-for="(b, index) in branches">
											<td class="!text-xs p-1">{{ b.address }}</td>
											<td class="!text-xs p-1">{{ b.indentifier }}</td>
											<td class="!text-xs p-1">
												<button class="btn btn-link p-0 px-2 !text-xs btn-block" @click="openViewTerms">View Terms</button>
											</td>
											<td class="!text-xs p-1">{{ b.phone }}</td>
											<td class="!text-xs p-1">{{ b.fax }}</td>
											<td class="!text-xs p-1">{{ b.contact_person }}</td>
											<td class="!text-xs p-1">{{ b.email }}</td>
											<td class="!text-xs p-1">{{ b.tin }}</td>
											<td class="!text-xs p-1">{{ b.type }}</td>
											<td class="!text-xs p-1">{{ b.ewt }}</td>
											<td class="!text-xs p-1">{{ (b.vat == 1) ? 'Vat' : 'Non-Vat' }}</td>
											<td class="!text-xs p-1">{{ b.status }}</td>
											<td class="!text-xs p-1">{{ b.notes }}</td>
											<td class="!text-xs p-0" align="center">
												<!-- <button class="btn-info btn btn-xs text-white p-1" @click="openEdit()">
													<PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3"></PencilIcon>
												</button> -->
												<button class="btn btn-danger p-1" @click="RemoveModal(index)">
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
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
									<button type="submit" @click="SaveNewVendor()" id = "SubmitButton" class="btn btn-primary mr-2 w-44" disabled>Save</button>
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
									<textarea class="form-control" placeholder="Address" v-model="address"></textarea>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Identifier</label>
									<textarea class="form-control" placeholder="Indetifier" v-model="identifier"></textarea>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<table class="table-bordered !text-xs w-full mb-3">
									<tr>
										<td class="p-1 bg-gray-100" colspan="3">Terms and Conditions</td>
									</tr>
									<tr>
										<td class="p-0" colspan="2">
											<input type="text" class="p-1 w-full bg-yellow-50" v-model="terms_text" id="check_terms">
										</td>
										<td class="p-0" width="1">
											<button type="button" class="btn btn-primary p-1" @click="addRowTerms">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
									<tr>
										<td class="p-1 align-top text-center" width="4%">1.</td>
										<td class="p-1 align-top" colspan="2">PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.</td>
									</tr>
									
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Phone Number</label>
									<input type="text" class="form-control" placeholder="Phone Number" v-model="phone">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Fax Number</label>
									<input type="text" class="form-control" placeholder="Fax Number" v-model="fax">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Contact Person</label>
									<input type="text" class="form-control" placeholder="Contact Person" v-model="contact_person">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Email</label>
									<input type="email" class="form-control" placeholder="Email" v-model="email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >TIN</label>
									<input type="text" class="form-control" placeholder="TIN" v-model="tin">
								</div>
							</div>
							<div class="col-lg-6  col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Type</label>
									<input type="text" class="form-control" placeholder="Type" v-model="type">
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >EWT%</label>
									<input type="text" class="form-control" placeholder="EWT%" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" v-model="ewt">
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0">Status</label>
									<select class="form-control" v-model="status">
										<option selected="status=='Active'" value="Active">Active</option>
										<option selected="status=='Inactive'" value="Inactive">Inactive</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="flex !justify-center mt-4 space-x-4">
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >Vat</label>
										<input v-model="vat" value="1" type="radio" class="form-control !w-5 !h-5">
									</div>
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >Non-Vat</label>
										<input v-model="vat" value="0" type="radio" class="form-control !w-5 !h-5">
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Notes</label>
									<textarea class="form-control" placeholder="Notes" v-model="notes"></textarea>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/" class="btn btn-primary mr-2 w-44">Save</a> -->
									<button @click="AddNewBranch()" class="btn btn-primary mr-2 w-44">Add</button>
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
			<div class="modal pt-4 px-3 " :class="{ show:viewTermsModal }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">View Terns and Condition</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12">
								<p class="m-0 leading-none uppercase font-bold">Vendor Name Here</p>
								<p class="text-xs uppercase">Products and Services here</p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<p class="m-0 leading-none  font-bold text-xs">Branch Address</p>
								<p class="text-xs ">Contact Person | Contact Number</p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<table class="table-bordered text-xs w-full">
									<tr>
										<td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
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
											<div class="flex justify-start space-x-1">
												<span >Price is </span>
												<span>Exclusive of VAT</span>
											</div>
										</td>
									</tr>
									<tr>
										<td class="align-top text-center" width="4%">4.</td>
										<td class="align-top  pl-1" colspan="2">
											<div class="flex justify-start space-x-1">
												<span>Payment </span>
												<span> COD</span>
											</div>
										</td>
									</tr>
									<tr>
										<td class="align-top text-center" width="4%">5.</td>
										<td class="align-top  pl-1" colspan="2">
											<div class="flex justify-start space-x-1">
												<span>Delivery Term </span>
												<span> Sample</span>
											</div>
										</td>
									</tr>
									<tr>
										<td class="align-top text-center" width="4%">6.</td>
										<td class="align-top  pl-1" colspan="2">sample term</td>
									</tr>
								</table>
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
			<div class="modal pt-4 px-3 " :class="{ show:termsModal }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-11/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Terns and Condition</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-6">
								<p class="m-0 leading-none uppercase font-bold">Vendor Name Here</p>
								<p class="text-xs uppercase">Products and Services here</p>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4 border-r">
								<table class="table-bordered !text-xs w-full">
									<tr>
										<td class="p-1 bg-gray-100" colspan="3">Terms and Conditions</td>
									</tr>
									<tr>
										<td class="p-0" colspan="2">
											<input type="text" class="p-1 w-full bg-yellow-50" v-model="terms_text" id="check_terms">
										</td>
										<td class="p-0" width="1">
											<button type="button" class="btn btn-primary p-1" @click="addRowTerms">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
									<tr>
										<td class="p-1 align-top text-center" width="4%">1.</td>
										<td class="p-1 align-top" colspan="2">PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.</td>
									</tr>
									
								</table>
							</div>
							<div class="col-lg-8">
								<table class="table-bordered text-xs w-full"> 
									<tr>
										<td colspan="5" class="p-1 bg-gray-100">
											Choose Branch to apply Terms and Condition
										</td>
									</tr>
									<tr class="bg-gray-50">
										<td class="p-1" width="1%">
											<input type="checkbox">
										</td>
										<td class="p-1" width="40%">Address</td>
										<td class="p-1" width="15%">Contact Person</td>
										<td class="p-1" width="15%">Contact Number</td>
										<td class="p-1" width="15%">Email</td>
									</tr>
									<tr>
										<td class="p-1" width="1%">
											<input type="checkbox">
										</td>
										<td class="p-1" width="40%">Sample Address, Bacolod City</td>
										<td class="p-1" width="15%">Glenn Marie Sy</td>
										<td class="p-1" width="15%">093342425/ 524461414</td>
										<td class="p-1" width="15%">sample@gmail.com</td>
									</tr>

									<tr>
										<td class="p-1" width="1%">
											<input type="checkbox">
										</td>
										<td class="p-1" width="40%">Sample Address, Bacolod City</td>
										<td class="p-1" width="15%">Glenn Marie Sy</td>
										<td class="p-1" width="15%">093342425</td>
										<td class="p-1" width="15%">sample@gmail.com</td>
									</tr>
									<tr>
										<td class="p-1" width="1%">
											<input type="checkbox">
										</td>
										<td class="p-1" width="40%">Sample Address, Bacolod City</td>
										<td class="p-1" width="15%">Glenn Marie Sy</td>
										<td class="p-1" width="15%">093342425</td>
										<td class="p-1" width="15%">sample@gmail.com</td>
									</tr>
									<tr>
										<td class="p-1" width="1%">
											<input type="checkbox">
										</td>
										<td class="p-1" width="40%">Sample Address, Bacolod City</td>
										<td class="p-1" width="15%">Glenn Marie Sy</td>
										<td class="p-1" width="15%">093342425/ 524461414</td>
										<td class="p-1" width="15%">sample@gmail.com</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/" class="btn btn-primary mr-2 w-44">Save</a> -->
									<button @click="AddNewBranch()" class="btn btn-primary mr-2 w-44">Add</button>
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
		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:RemoveAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Do you really want to delete this row?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeModal()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="removebranch(remove_id)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">You have successfully created a new Vendor.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="CreateNewVendor">Create New</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full"  @click="ShowList">Vendor List</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:VendorHeadAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight" v-if="vendor_name == ''">Vendor Name is required!</h5>
									<h5 class="leading-tight" v-else>Vendor Name is already existing!</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeModal()">Update</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="ShowList()">Cancel</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:AddBranchAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">You must add atleast 1 branch!</h5>

								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeModal()">Update</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="ShowList()">Cancel</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successBranchAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">You have successfully added a new Branch.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="AddBranch()">Add New</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full"  @click="closeModal()">Close</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:BranchAddressAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Address is required!</h5>
									<!-- <h5 class="leading-tight" v-if="address.value == ''">Address is required!</h5>
									<h5 class="leading-tight" v-else>Address is already existing!</h5> -->
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="UpdateAddress()">Update</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeModal()">Cancel</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>