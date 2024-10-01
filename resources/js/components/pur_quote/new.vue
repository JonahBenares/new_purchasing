<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
	import axios from 'axios';
    import { onMounted, ref } from "vue"
	import { useRouter } from "vue-router";
	const router = useRouter();

	let prnolist=ref([]);
	let vendorlist=ref([]);
	let pr_no=ref([]);
	let vendor_list=ref([]);
	let PRHead=ref([]);
	let PRDetails=ref([]);
	let selected_items=ref([]);
	let rfq_name=ref('');
	let rfq_no=ref('');
	let date_created=ref('');
	let vendor=ref('');

	onMounted(async () => {
		getprno()
		getvendors()
		getPRDetails()
	})

	const getprno = async () => {
			let response = await axios.get("/api/pr_list");
			prnolist.value=response.data
	}

	const getvendors = async () => {
			let response = await axios.get("/api/vendor_list");
			vendorlist.value=response.data
	}

	const getPRDetails = async () => {
			let id = pr_no.value
			let response = await axios.get('/api/get_pr_data/'+id)
			PRHead.value = response.data.PRHead
			PRDetails.value = response.data.PRDetails
			rfq_no.value = response.data.rfq_no
			// currency.value = response.data.currency
		
	}

	const showModal = ref(false)
	const hideModal = ref(true)

	const closeModal = () => {
		showModal.value = !hideModal.value
		selected_items.value=[]
	}

	const allSelected=ref(false);
	const checkall=ref([]);
	const CheckAll = () => {
			var count_check=document.getElementsByClassName('checkboxes');
			for(var x=0;x<count_check.length;x++){
				var check=document.getElementsByClassName('checkboxes')[x].checked;
				if(!check){
					checkall.value=allSelected
					PRDetails.value[x].checkbox=1;
				}else{
					checkall.value=!allSelected
				}
			}
	}

	const addItemsVendor= () => {
		for(var x=0; x<PRDetails.value.length; x++){
				if(PRDetails.value[x].checkbox == 1 && PRDetails.value[x].checkbox){
					selected_items.value.push({
						pr_details_id:PRDetails.value[x].pr_details_id,
						quantity:PRDetails.value[x].quantity,
						uom:PRDetails.value[x].uom,
						pn_no:PRDetails.value[x].pn_no,
						item_description:PRDetails.value[x].item_description,
						wh_stocks:PRDetails.value[x].wh_stocks,
					});
				}
			}
		showModal.value = !showModal.value
	}

	const addVendor= () => {
		// for(var x=0; x<vendor_list.value.length; x++){
		// 	if(document.getElementById("vendor_name"+x).value == vendor_name.value){
		// 		var vendor_count = 1;
		// 	}
		// }

			// if(vendor_count != undefined){
			// 	alert("The vendor is already added!")
			// }else if(vendor_name.value == ''){
			// 	alert("You must select Vendor!")
			// }else{

				let ven = vendor.value
				const v = ven.split("_")
				let vendor_details_id= v[0]
				let vendor_name= v[2]
				let identifier= v[3]

				const vendors = {
					vendor_details_id:vendor_details_id,
					vendor_name:vendor_name,
					identifier:identifier,
				}
				vendor_list.value.push(vendors)

				// vendor_list.value.forEach(function (val, index, theArray) {
				// 	if(document.getElementById("v_name"+index).value == vendor_name.value){
				// 		alert("This vendor is already added!")
				// 		vendor_list.value.splice(index,1)
				// 	}
				// });
				// vendor_name.value='';
			// }
	}

	const SaveandProceed= () => {
		// if(confirm("Are you sure you want to update this Vendor?")){
		const formRFQ= new FormData()
			formRFQ.append('pr_head_id', PRHead.value.pr_head_id)
			formRFQ.append('pr_no', PRHead.value.pr_no)
			formRFQ.append('rfq_name', rfq_name.value)
			formRFQ.append('rfq_no', rfq_no.value)
			formRFQ.append('rfq_date', date_created.value)
			formRFQ.append('user_id', PRHead.value.user_id)
			formRFQ.append('rfq_items', JSON.stringify(selected_items.value))
			formRFQ.append('rfq_vendors', JSON.stringify(vendor_list.value))
			axios.post("/api/add_rfq", formRFQ).then(function (response) {
				router.push('/pur_quote/print/'+response.data)
				// successAlert.value = !successAlert.value
			});
	// }
	}

	

	const removeVendor = (index) => {
		vendor_list.value.splice(index,1)
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Request for Quotation <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_quote">Request for Quotation</a></li>
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
								<label class="text-gray-500 m-0" for="">Choose PR Number</label>
								<input type="file" name="img[]" class="file-upload-default">
								<div class="input-group col-xs-12">
									<select class="form-control file-upload-info" v-model="pr_no">
                                        <option :value="pr.id" v-for="pr in prnolist" :key="pr.id">{{ pr.pr_no }}</option>
                                    </select>
									<span class="input-group-append">
										<button class="btn btn-primary" type="button" @click="getPRDetails()">Select</button>
									</span>
								</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div v-if="PRHead != undefined">
							<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Purchase Request: </span>
								<span class="text-sm text-gray-700">{{ PRHead.location }}</span>
							</div>
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
								<span class="text-sm text-gray-700">{{ PRHead.date_prepared }}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">PR Number: </span>
								<span class="text-sm text-gray-700">{{ PRHead.site_pr }}</span>
							</div>
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">New PR Number: </span>
								<span class="text-sm text-gray-700">{{ PRHead.pr_no }}</span>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
								<span class="text-sm text-gray-700">{{ PRHead.department_name }}</span>
							</div>
							<div class="col-lg-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
								<span class="text-sm text-gray-700">{{ PRHead.process_code }}</span>
							</div>
							<div class="col-lg-2">
								<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
								<span class="text-sm text-gray-700">{{ PRHead.urgency }}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
								<span class="text-sm text-gray-700">{{ PRHead.enduse }}</span>
							</div>
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
								<span class="text-sm text-gray-700">{{ PRHead.purpose }}</span>
							</div>
						</div>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mb-3">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="5%">
												<input type="checkbox" id="checkall" @click="CheckAll" :checked="allSelected">
											</td>
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
										</tr>
										<tr v-for="d in PRDetails">
											<td class="p-1 text-center">
												<input type="checkbox" class='checkboxes' v-model="d.checkbox" :checked="checkall" :true-value="1" :false-value="0">
											</td>
											<input type="hidden" v-model="d.pr_details_id">
											<td class="p-1 text-center">{{ d.quantity }}</td>
											<td class="p-1 text-center">{{ d.uom }}</td>
											<td class="p-1">{{ d.pn_no }}</td>
											<td class="p-1">{{ d.item_description }}</td>
											<td class="p-1">{{ d.wh_stocks }}</td>
											<td class="p-1">{{ d.date_needed }}</td>
										</tr>
									</table>
								</div>
							</div>
						
							<br>
							<div class="row my-2"> 
								<div class="col-lg-12 col-md-12">
									<div class="flex justify-center space-x-2">
										<button type="submit" @click="addItemsVendor()" class="btn btn-primary mr-2 w-44">Add Vendor</button>
									</div>
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
									<input type="text" class="form-control" placeholder="RFQ Name" v-model="rfq_name">
								</div>
							</div>
							<div class="col-lg-6 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">RFQ No</label>
									<input type="text" class="form-control" placeholder="RFQ No" v-model="rfq_no">
								</div>
							</div>
							<div class="col-lg-6 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Date Created</label>
									<input type="text" class="form-control" placeholder="Date Created"  onfocus="(this.type='date')" v-model="date_created">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mb-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="7%">Qty</td>
										<td class="p-1 uppercase text-center" width="7%">UOM</td>
										<td class="p-1 uppercase" width="20%">PN No.</td>
										<td class="p-1 uppercase" width="">Item Description</td>
										<td class="p-1 uppercase" width="10%">WH Stocks</td>
										<td class="p-1 uppercase" width="15%">Date Needed</td>
									</tr>
									<tr v-for="d in selected_items">
										<input type="hidden" v-model="d.pr_details_id">
										<td class="p-1 text-center">{{ d.quantity }}</td>
										<td class="p-1 text-center">{{ d.uom }}</td>
										<td class="p-1">{{ d.pn_no }}</td>
										<td class="p-1">{{ d.item_description }}</td>
										<td class="p-1">{{ d.wh_stocks }}</td>
										<td class="p-1">{{ d.date_needed }}</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<table class="table-bordered !w-full"  >
									<tr class="bg-gray-100">
										<td class="p-1 px-2 text-sm">Add Vendor</td>
										<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
											<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
										</td>
									</tr>
									<tr>
										<!-- <td class=""><input placeholder="Vendor" type="text" v-model="vendor_name" class="w-full text-sm p-1 px-2"></td> -->
										<select class="form-control" v-model="vendor">
                                        	<option :value="v.vendor_details_id+'_'+v.vendor_head_id+'_'+v.vendor_name+'_'+v.identifier" v-for="v in vendorlist" :key="v.vendor_details_id">{{ v.vendor_name }} {{ (v.identifier != '') ? '('+v.identifier+')' : '' }}</option>
										</select>
										<td class="text-center">
											<button type="button" class="btn btn-primary p-1" @click="addVendor">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
									<tr v-for="(v,index) in vendor_list">
										<td class="text-sm p-1 px-2">
											{{ v.vendor_name }} {{ (v.identifier != '') ? '('+v.identifier+')' : '' }}
											<input type="hidden" class="p-1 m-0 w-full leading-none vname" :id="'v_name'+index" v-model="v.vendor_name"/>
											<input type="hidden" class="p-1 m-0 w-full leading-none vname" v-model="v.vendor_details_id"/>
										</td>
										<td class="text-center">
											<button class="btn btn-danger p-1" @click="removeVendor(index)">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<!-- <tr>
										<td class="text-sm p-1 px-2">Lectrix Solutions Electrical Supplies & Services Cebu</td>
										<td class="text-center">
											<button class="btn btn-danger p-1">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>

									<tr>
										<td class="text-sm p-1 px-2">MF Computer Solutions, Inc.</td>
										<td class="text-center">
											<button class="btn btn-danger p-1">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr>
										<td class="text-sm p-1 px-2">Nexus Industrial Prime Solutions Corp.</td>
										<td class="text-center">
											<button class="btn btn-danger p-1">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr> -->
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_quote/print" class="btn btn-primary mr-2 w-44">Save and Proceed</a> -->
									<button type="submit" @click="SaveandProceed()" class="btn btn-primary mr-2 w-44">Save and Proceed</button>
									<button type="submit" @click="closeModal()" class="btn btn-danger mr-2 w-44">Close</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>