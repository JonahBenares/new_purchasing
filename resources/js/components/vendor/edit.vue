<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, XMarkIcon, PencilIcon, CheckIcon, PlusIcon, EllipsisVerticalIcon, PencilSquareIcon} from '@heroicons/vue/24/solid'
	import {onMounted, ref} from "vue";
	import { useRouter } from "vue-router";
	const router = useRouter();

	let head = ref({
        id:''
    })
	let branches=ref([]);
	let branch_dets=ref([]);
	let all_term_list=ref([]);
	let new_term_list=ref([]);
	let update_term_list=ref([]);
	let no_terms_branch=ref([]);
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
	let all_order_no=ref('');
	let all_term_desc=ref('');
	let new_order_no=ref('');
	let new_term_desc=ref('');
	let update_order_no=ref('');
	let update_term_desc=ref('');
	let count_branches=ref('');
	let count_with_terms=ref('');

	const props = defineProps({
        id:{
            type:String,
            default:''
        }
    })

	onMounted(async () => {
		VendorDetails()
	})

	const VendorDetails = async () => {
		let response = await axios.get(`/api/edit_vendor/${props.id}`)
       	head.value = response.data.vendor_head
       	branches.value = response.data.vendor_details
       	count_branches.value = response.data.count_branches
       	count_with_terms.value = response.data.count_with_terms
	}

	const modalNew = ref(false)
    const modalEdit = ref(false)
	const successAlert = ref(false)
	const successBranchAlert = ref(false)
	const successAddBranchAlert = ref(false)
	const VendorHeadAlert = ref(false)
    const AddBranchAlert = ref(false)
	const BranchAddressAlert = ref(false)
	const IdentifierAlert = ref(false)
    const RemoveAlert = ref(false)
    const termsModal = ref(false)
    const viewTermsModal = ref(false)
	const TermsAlert = ref(false)
	const AllTermsSuccess = ref(false)
	const hideModal = ref(true)
	const moreDetails = ref(false)
	const openNew = () => {
		modalNew.value = !modalNew.value
	}

	const openTerms = async () => {
		termsModal.value = !termsModal.value
		let response = await axios.get(`/api/no_terms_branch/${props.id}`)
		no_terms_branch.value = response.data.no_terms_branch
	}

	const openViewTerms = () => {
		viewTermsModal.value = !viewTermsModal.value
	}
	const closeModal = () => {
		viewTermsModal.value = !hideModal.value
		modalNew.value = !hideModal.value
		termsModal.value = !hideModal.value
		modalEdit.value = !hideModal.value
		RemoveAlert.value = !hideModal.value
		successAlert.value = !hideModal.value
		successBranchAlert.value = !hideModal.value
		BranchAddressAlert.value = !hideModal.value
		successAddBranchAlert.value = !hideModal.value
		VendorHeadAlert.value = !hideModal.value
		BranchAddressAlert.value = !hideModal.value
		IdentifierAlert.value = !hideModal.value
		successBranchAlert.value = !hideModal.value
		TermsAlert.value = !hideModal.value
		AllTermsSuccess.value = !hideModal.value
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

	const closeUpdateModal = () => {
		modalNew.value = !hideModal.value
		modalEdit.value = !hideModal.value
		RemoveAlert.value = !hideModal.value
		successAlert.value = !hideModal.value
		successBranchAlert.value = !hideModal.value
		successAddBranchAlert.value = !hideModal.value
		VendorHeadAlert.value = !hideModal.value
		BranchAddressAlert.value = !hideModal.value
		IdentifierAlert.value = !hideModal.value
		successBranchAlert.value = !hideModal.value
		termsModal.value = !hideModal.value
		AllTermsSuccess.value = !hideModal.value
		all_term_list.value=[]
		new_term_list.value=[]
		VendorDetails()
	}

	const UpdateBranchAgain = () => {
		successBranchAlert.value = !hideModal.value
	}

	const RemoveModal = (index) => {
		console.log(index)
		RemoveAlert.value = !RemoveAlert.value
		remove_id.value = index
		
	}

	const ShowList = () => {
		router.push('/vendor/')
	}

	const removebranch= (index) =>{
		branches.value.splice(index,1)
		closeModal()
	}

	const RemoveAllTerms= (index) =>{
		all_term_list.value.splice(index,1)
		// if(index>=1){
		// 	document.getElementById("AllTerms").disabled = false;
		// }else{
		// 	document.getElementById("AllTerms").disabled = true;
		// }
	}

	const CheckBoxChecker= () =>{
		var isChecked = document.getElementsByClassName("checkboxes");
		var count=0;
		for(var x=0;x<isChecked.length;x++){
			if(isChecked[x].checked === true){
				count++;
			}
		}
			if(count>=1){
				document.getElementById("AllTerms").disabled = false;
			}else{
				document.getElementById("AllTerms").disabled = true;
			}
	}


	const RemoveNewTerms= (index) =>{
		new_term_list.value.splice(index,1)
	}

	const RemoveUpdateTerms= (index) =>{
		update_term_list.value.splice(index,1)
	}

	const UpdateTerms = () => {
		TermsAlert.value = !hideModal.value
	}

    const EditBranch = async (id) => {
		modalEdit.value = !modalEdit.value
		let response = await axios.get('/api/edit_branch/'+id)
       	branch_dets.value = response.data.branch_details
       	update_term_list.value = response.data.branch_terms
	}

	const UpdateAddress = () => {
		BranchAddressAlert.value = !hideModal.value
	}

	const UpdateIdentifier = () => {
		IdentifierAlert.value = !hideModal.value
	}

	const AddBranch = () => {
		successAddBranchAlert.value = !hideModal.value
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
		}else if(identifier.value == ''){
			IdentifierAlert.value = !IdentifierAlert.value
		}else{
			successAddBranchAlert.value = !successAddBranchAlert.value
			branches.value.push({
			address:address.value,
			identifier:identifier.value,
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
			terms:new_term_list.value,
			id:0,
			});
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
		new_term_list.value=[]
		}
	}

	const AddAllTerms= () => {
		if(all_term_desc.value == ''){
			TermsAlert.value = !TermsAlert.value
		}else{
		let all_orderno= all_term_list.value.length
				all_term_list.value.push({
					all_order_no:all_orderno+1,
					all_term_desc:all_term_desc.value,
				});
				// if(all_term_list.value.length>=1){
				// 	document.getElementById("AllTerms").disabled = false;
				// }
				all_order_no.value=''
				all_term_desc.value=''
		}
	}

	const AddTermsUpdate= () => {
		if(update_term_desc.value == ''){
			TermsAlert.value = !TermsAlert.value
		}else{
		let update_orderno= update_term_list.value.length
				update_term_list.value.push({
					id:0,
					order_no:update_orderno+1,
					terms:update_term_desc.value,
				});
				update_order_no.value=''
				update_term_desc.value=''
		}
	}

	const AddTermsNew= () => {
		if(new_term_desc.value == ''){
			TermsAlert.value = !TermsAlert.value
		}else{
		let new_orderno= new_term_list.value.length
				new_term_list.value.push({
					order_no:new_orderno+1,
					terms:new_term_desc.value,
				});
				new_order_no.value=''
				new_term_desc.value=''
		}
	}

	const UpdateBranch = (id) => {
		const formData= new FormData()
		formData.append('vendor_head_id',head.value.id)
		formData.append('phone', branch_dets.value.phone ?? '')
		formData.append('fax', branch_dets.value.fax ?? '')
		formData.append('contact_person', branch_dets.value.contact_person ?? '')
		formData.append('email', branch_dets.value.email ?? '')
		formData.append('notes', branch_dets.value.notes ?? '')
		formData.append('ewt', branch_dets.value.ewt ?? 0)
		formData.append('vat', branch_dets.value.vat)
		formData.append('status', branch_dets.value.status)
		formData.append('terms', JSON.stringify(update_term_list.value))
			axios.post(`/api/update_branch/`+id, formData).then(function () {
				successBranchAlert.value = !successBranchAlert.value
			});
    }

	const UpdateVendor = (id) => {
		// if(confirm("Are you sure you want to update this Vendor?")){
		if(head.value.vendor_name != ''){
			const formVendor= new FormData()
				formVendor.append('head_id',head.value.id)
				formVendor.append('vendor_name',head.value.vendor_name)
				formVendor.append('product_services',head.value.product_services)
				formVendor.append('vendor_branches', JSON.stringify(branches.value))
				axios.post(`/api/update_vendor/`+id, formVendor).then(function () {
					successAlert.value = !successAlert.value
				}, function (err) {
					VendorHeadAlert.value = !VendorHeadAlert.value
				});
		// }
		}else{
			VendorHeadAlert.value = !VendorHeadAlert.value
		}
	}

	const AddAllBranchTerms = () => {
			const formTerms= new FormData()
				formTerms.append('vendor_head_id',head.value.id)
				if(all_term_list.value.length>=1){
					formTerms.append('no_terms_branch', JSON.stringify(no_terms_branch.value))
					formTerms.append('all_term_list', JSON.stringify(all_term_list.value))
					// console.log(JSON.stringify(no_terms_branch.value))
					axios.post('/api/add_all_terms/', formTerms).then(function () {
						AllTermsSuccess.value = !AllTermsSuccess.value
					});
				}else{
					TermsAlert.value = !TermsAlert.value
				}
	}

	const VendorChecker = async () => {
		let response = await axios.get("/api/check_vendor_name/"+head.value.vendor_name);
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
	const allSelected=ref(false);
	const checkall=ref([]);
	const CheckAll = () => {
			var count_check=document.getElementsByClassName('checkboxes');
			var checkedValue = null; 
			for(var x=0;x<count_check.length;x++){
				var check=document.getElementsByClassName('checkboxes')[x].checked;
				if(!check){
					checkall.value=allSelected
					no_terms_branch.value[x].checkbox=1;
					document.getElementById("AllTerms").disabled = false;
				}else{
					checkall.value=!allSelected
					document.getElementById("AllTerms").disabled = true;
				}
			}
	}

	const ShowBranchDetails= (index) => {
		var showdetails = document.getElementById("branch_dets_disp"+index);
		var displaySetting = showdetails.style.display;
		if (displaySetting == 'block') {
			showdetails.style.display = 'none';
		}else {
			showdetails.style.display = 'block';
		}
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
									<textarea class="form-control" placeholder="Vendor Name" v-model="head.vendor_name" @change="VendorChecker()"></textarea>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Products/Services</label>
									<textarea class="form-control" placeholder="Products/Services" v-model="head.product_services"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="flex justify-start  mt-2 mb-3  z-50 space-x-2">
									<button @click="openNew()" class="btn btn-primary btn-sm mt-2 mt-xl-0 text-white">
										<span>Add Branch</span>
									</button>
									<button @click="openTerms()" class="btn btn-primary btn-sm mt-2 mt-xl-0 text-white" v-if="(count_branches != count_with_terms)">
										<span>Add Terms</span>
									</button>
								</div>
								<div class="border" v-for="(b, index) in branches" :class="(b.status=='Inactive') ? 'bg-red-100' : ''">
									<div class="flex justify-between px-3 py-2 ">
										<!-- <button class="w-full !text-left" @click="moreDetails =!moreDetails"> -->
											<button class="w-full !text-left" @click="ShowBranchDetails(index)">
											<div class=" w-full bg-gren-50">
												<p class="mb-1 text-gray-600 font-bold text-sm">{{ b.address}}</p>
												<table class="w-full text-xs">
													<tr>
														<td width="20%">
															<span class="pr-1"><span class="text-gray-800">Identifier :</span> {{ b.identifier}}</span>
														</td>
														<td width="20%">
															<span class="pr-1"><span class="text-gray-800">Email :</span> {{ b.email}}</span>
														</td>
													</tr>
													<tr>
														<td width="20%">
															<span class="pr-1"><span class="text-gray-800">Contact Person :</span> {{ b.contact_person}}</span>
														</td>
														<td width="20%">
															<span class="pr-1"><span class="text-gray-800">Contact Number :</span> {{ b.phone}}</span>
														</td>
														<td width="20%">
															<span class="pr-1"><span class="text-gray-800">Fax :</span> {{ b.fax}}</span>
														</td>
													</tr>
												</table>
												<!-- <div class="flex justify-between space-x-1 text-xs text-gray-600">
													<span class="pr-1"><span class="text-gray-800">TIN :</span> {{ b.tin}}</span>
													<span class="pr-1"><span class="text-gray-800">Contact Person :</span> {{ b.contact_person}}</span>
													<span class="pr-1"><span class="text-gray-800">Contact Number :</span> {{ b.phone}}</span>
													<span class="pr-1"><span class="text-gray-800">Fax :</span> {{ b.fax}}</span>
													<span class="pr-1"><span class="text-gray-800">Email :</span> {{ b.email}}</span>
												</div> -->
											</div>
										</button>
										<div class="w-4 pr-2">
											<button class="text-gray-500 mr-2" @click="EditBranch(b.id)" v-if="(b.id != 0)">
												<PencilSquareIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></PencilSquareIcon>
											</button>
											<button class="btn btn-danger p-1" @click="RemoveModal(index)" v-else>
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</div>
										
									</div>
									<!-- <div class="" v-show="moreDetails"> -->
									<div class="" style="display: none;" :id="'branch_dets_disp'+index">
										<hr class="border-dashed m-0">
										<div class="row">
											<div class="col-lg-5 border-r">
												<div class="px-3 py-2">
													<table class="w-full text-xs">
														<tr>
															<td class="!text-gray-800" width="13%">TIN </td>
															<td></td>
															<td>  {{ b.tin}}</td>
														</tr>
														<tr>
															<td class="!text-gray-800">Type</td>
															<td></td>
															<td>  {{ b.type}}</td>
														</tr>
														<tr>
															<td class="!text-gray-800">EWT</td>
															<td></td>
															<td> {{ parseFloat(b.ewt).toFixed(2) }} </td>
														</tr>
														<tr>
															<td class="!text-gray-800">VAT</td>
															<td></td>
															<td>  {{ (b.vat == '1') ? 'Vat' : 'Non-vat'}}</td>
														</tr>
														<tr>
															<td class="!text-gray-800">Status</td>
															<td></td>
															<td>  {{ b.status}}</td>
														</tr>
														<tr>
															<td class="!text-gray-800">Notes</td>
															<td></td>
															<td>  {{ b.notes}}</td>
														</tr>
													</table>
												</div>
											</div>
											<div class="col-lg-7 pl-0">
												<div class="px-3 py-2">
													<table class="table-basdordered text-xs w-full">
														<tr>
															<td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
														</tr>
														<tr  v-for="(t, indexes) in b.terms">
															<td class="align-top text-center" width="4%">{{ t.order_no }}.</td>
															<td class="align-top px-1" colspan="2">{{ t.terms }}</td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" v-model="b.id">
								</div>
								<br>
								<!-- <div class="overflow-x-scroll">
									<table class="border table-bordered" width="180%">
										<tr>
											<th class="!text-xs p-1 bg-gray-100" width="15%"> Address</th>
											<th class="!text-xs p-1 bg-gray-100" width="10%"> Identifier</th>
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
											<th class="!text-xs p-1 bg-gray-100" width="12%" v-if="(branches.id != 0)"> Terms</th>
											<th class="!text-xs p-1 bg-gray-100" width="1%"> 
												<span class="text-center justify-center flex px-auto">
													<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
												</span>
											</th>
										</tr>
										<tr v-for="(b, index) in branches">
											<td class="!text-xs p-1">{{ b.address}}</td>
											<td class="!text-xs p-1">{{ b.identifier}}</td>
											<td class="!text-xs p-1">{{ b.phone}}</td>
											<td class="!text-xs p-1">{{ b.fax}}</td>
											<td class="!text-xs p-1">{{ b.contact_person}}</td>
											<td class="!text-xs p-1">{{ b.email}}</td>
											<td class="!text-xs p-1">{{ b.tin}}</td>
											<td class="!text-xs p-1">{{ b.type}}</td>
											<td class="!text-xs p-1">{{ b.ewt}}</td>
											<td class="!text-xs p-1">{{ (b.vat == '1') ? 'Vat' : 'Non-vat'}}</td>
											<td class="!text-xs p-1">{{ b.status}}</td>
											<td class="!text-xs p-1">{{ b.notes}}</td>
											<td class="!text-xs p-1">
												<ul class="list-disc m-0" v-for="(t, indexes) in b.terms">
													{{ t.order_no }}. {{ t.terms }}
												</ul>
											</td>
											<input type="hidden" v-model="b.id">
											<td class="!text-xs p-0" align="center">
												<button class="btn-info btn btn-xs text-white p-1" @click="EditBranch(b.id)" v-if="(b.id != 0)">
													<PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3"></PencilIcon>
												</button>
												<button class="btn btn-danger p-1" @click="RemoveModal(index)" v-else>
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</td>
										</tr>
									</table>
								</div> -->
							</div>
						</div>
						<hr class="border-dashed">
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" @click="UpdateVendor(head.id)" class="btn btn-info mr-2 w-44">Update</button>
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
									<textarea class="form-control" placeholder="Identifier" v-model="identifier"></textarea>
								</div>
							</div>
							<!-- <div class="col-lg-12 col-md-12">
								<table class="table-bordered !text-xs w-full mb-3">
									<tr>
										<td class="p-1 bg-gray-100" colspan="3">Terms and Conditions</td>
									</tr>
									<tr>
										<td class="p-0" colspan="2">
											<input type="text" class="p-1 w-full bg-yellow-50" id="check_terms">
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
							</div> -->
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
										<option selected="status=='Inactive'" value="Inactive">Inactive</option>
										<option selected="status=='Active'" value="Active">Active</option>
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
						<div class="col-lg-12 col-md-12">
							<table class="table-bordered !text-xs w-full mb-3">
								<tr>
									<td class="p-1 bg-gray-100" colspan="3">Terms and Conditions</td>
								</tr>
								<tr>
									<td class="p-0" colspan="2">
										<input type="text" class="p-1 w-full bg-yellow-50" v-model="new_term_desc">
									</td>
									<td class="p-0" width="1">
										<button type="button" class="btn btn-primary p-1" @click="AddTermsNew">
											<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
										</button>
									</td>
								</tr>
								<tr v-for="(nt, index) in new_term_list">
									<td class="p-1 align-top" width="6%"><input type="text" class="form-control" v-model="nt.order_no"></td>
									<td class="p-1 align-top" colspan="1"><input type="text" class="form-control" v-model="nt.terms"></td>
									<td class="p-1 align-top" colspan="1">
									<button class="btn btn-danger p-1" @click="RemoveNewTerms(index)">
										<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
									</button>
									</td>
								</tr>
								
							</table>
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
							<span class="font-bold ">View Terms and Condition</span>
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
							<span class="font-bold ">Add Terms and Condition</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-6">
								<p class="m-0 leading-none uppercase font-bold">{{ head.vendor_name }}</p>
								<p class="text-xs uppercase">{{ head.product_services }}</p>
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
											<input type="text" class="p-1 w-full bg-yellow-50" v-model="all_term_desc">
										</td>
										<td class="p-0" width="1">
											<button type="button" class="btn btn-primary p-1" @click="AddAllTerms">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
									<tr v-for="(at, index) in all_term_list">
									<td class="p-1 align-top" width="10%"><input type="text" class="form-control" v-model="at.all_order_no"></td>
									<td class="p-1 align-top" colspan="1"><input type="text" class="form-control" v-model="at.all_term_desc"></td>
									<td class="p-1 align-top" colspan="1">
									<button class="btn btn-danger p-1" @click="RemoveAllTerms(index)">
										<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
									</button>
									</td>
								</tr>
								</table>
							</div>
							<div class="col-lg-8">
								<table class="table-bordered text-xs w-full"> 
									<tr>
										<td class="p-1" width="1%">
											<input type="checkbox" id="checkall" @click="CheckAll" :checked="allSelected">
										</td>
										<td colspan="5" class="p-1 bg-gray-100">
											Choose Branch to apply Terms and Condition
										</td>
									</tr>
									<tr v-for="(nt, index) in no_terms_branch">
										<td class="p-1" width="1%">
											<input type="checkbox" class='checkboxes' @change="CheckBoxChecker" v-model="nt.checkbox" :checked="checkall" :true-value="1" :false-value="0">
										</td>
										<input type="hidden" v-model="nt.id">
										<td class="p-1" width="40%">{{ nt.address }}</td>
										<td class="p-1" width="20%">{{ nt.identifier }}</td>
										<td class="p-1" width="15%">{{ nt.contact_person }}</td>
										<td class="p-1" width="15%">{{ nt.phone }}</td>
										<td class="p-1" width="15%">{{ nt.email }}</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/" class="btn btn-primary mr-2 w-44">Save</a> -->
									<button @click="AddAllBranchTerms()" id = "AllTerms" class="btn btn-primary mr-2 w-44" disabled>Add</button>
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
									<textarea class="form-control !bg-gray-200" placeholder="Address" v-model="branch_dets.address" readonly></textarea>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Identifer</label>
									<textarea class="form-control !bg-gray-200" placeholder="Identifier" v-model="branch_dets.identifier" readonly></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Phone Number</label>
									<input type="text" class="form-control" placeholder="Phone Number" v-model="branch_dets.phone">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Fax Number</label>
									<input type="text" class="form-control" placeholder="Fax Number" v-model="branch_dets.fax">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Contact Person</label>
									<input type="text" class="form-control" placeholder="Contact Person" v-model="branch_dets.contact_person">
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Email</label>
									<input type="email" class="form-control" placeholder="Email" v-model="branch_dets.email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >TIN</label>
									<input type="text" class="form-control !bg-gray-200" placeholder="TIN" v-model="branch_dets.tin" readonly>
								</div>
							</div>
							<div class="col-lg-6  col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Type</label>
									<input type="text" class="form-control !bg-gray-200" placeholder="Type" v-model="branch_dets.type" readonly>
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >EWT%</label>
									<input type="text" class="form-control" placeholder="EWT%" v-model="branch_dets.ewt">
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Status</label>
									<select class="form-control" v-model="branch_dets.status">
										<option selected="branch_dets.status=='Inactive'" value="Inactive">Inactive</option>
										<option selected="branch_dets.status=='Active'" value="Active">Active</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4 col-md-2">
								<div class="flex !justify-center mt-4 space-x-4">
									<!-- <div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >VAT</label>
										<input type="radio" name="vat" v-model="branch_dets.vat" class="form-control !w-5 !h-5" placeholder="Vat" checked v-if="branch_dets.vat=='1'">
										<input type="radio" name="vat" class="form-control !bg-gray-200 !w-5 !h-5" placeholder="Vat" v-else>
									</div>
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >Non-VAT</label>
										<input type="radio" name="vat" v-model="branch_dets.vat" class="form-control !w-5 !h-5" placeholder="Non-vat" checked v-if="branch_dets.vat=='0'">
										<input type="radio" name="vat" class="form-control !bg-gray-200 !w-5 !h-5" placeholder="Non-vat" v-else>
									</div> -->
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >Vat</label>
										<input v-model="branch_dets.vat" value="1" type="radio" class="form-control !w-5 !h-5">
									</div>
									<div class="form-group text-center flex justify-center space-x-2 pt-2">
										<label class="text-gray-500 m-0" >Non-Vat</label>
										<input v-model="branch_dets.vat" value="0" type="radio" class="form-control !w-5 !h-5">
									</div>
								</div>
							</div>
							
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Notes</label>
									<textarea class="form-control" placeholder="Notes" v-model="branch_dets.notes"></textarea>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12">
							<table class="table-bordered !text-xs w-full mb-3">
								<tr>
									<td class="p-1 bg-gray-100" colspan="3">Terms and Conditions</td>
								</tr>
								<tr>
									<td class="p-0" colspan="2">
										<input type="text" class="p-1 w-full bg-yellow-50" v-model="update_term_desc">
									</td>
									<td class="p-0" width="1">
										<button type="button" class="btn btn-primary p-1" @click="AddTermsUpdate">
											<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
										</button>
									</td>
								</tr>
								<tr v-for="(ut, index) in update_term_list">
									<td class="p-1 align-top" width="6%"><input type="text" class="form-control" v-model="ut.order_no"></td>
									<td class="p-1 align-top" colspan="1"><input type="hidden" class="form-control" v-model="ut.id"><input type="text" class="form-control" v-model="ut.terms"></td>
									<td class="p-1 align-top" colspan="1">
									<button class="btn btn-danger p-1" @click="RemoveUpdateTerms(index)" v-if="(ut.id == 0)">
										<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
									</button>
									</td>
								</tr>
								
							</table>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/" class="btn btn-primary mr-2 w-44">Save</a> -->
									<button @click="UpdateBranch(branch_dets.id)" class="btn btn-primary mr-2 w-44">Update</button>
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
									<h5 class="leading-tight">You have successfully updated this Vendor.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="ShowList">Vendor List</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" @click="closeUpdateModal()">Update</button>
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
									<h5 class="leading-tight" v-if="head.vendor_name == ''">Vendor Name is required!</h5>
									<h5 class="leading-tight" v-else>Vendor Name is already existing!</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="ShowList()">Cancel</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="closeModal()">Update</button>
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
									<h5 class="leading-tight">You have successfully updated this branch.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeUpdateModal()">Close</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" @click="ShowList()">Vendor List</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successAddBranchAlert }">
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
									<h5 class="leading-tight">You have successfully added a new branch.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">Close</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full"  @click="AddBranch()">Add New</button>
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
				<div @click="UpdateAddress" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">Cancel</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="UpdateAddress()">Update</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:IdentifierAlert }">
				<div @click="UpdateIdentifier" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">Identifier is required!</h5>
									<!-- <h5 class="leading-tight" v-if="address.value == ''">Address is required!</h5>
									<h5 class="leading-tight" v-else>Address is already existing!</h5> -->
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">Cancel</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="UpdateIdentifier()">Update</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:TermsAlert }">
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
									<h5 class="leading-tight">Terms and Conditions field is empty!</h5>
									<!-- <h5 class="leading-tight" v-if="address.value == ''">Address is required!</h5>
									<h5 class="leading-tight" v-else>Address is already existing!</h5> -->
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">Cancel</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="UpdateTerms()">Update</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:AllTermsSuccess }">
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
									<h5 class="leading-tight">You have successfully added a Terms and Condition.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="ShowList()">Vendor List</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full"  @click="closeUpdateModal()">Close</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
	</navigation>
</template>