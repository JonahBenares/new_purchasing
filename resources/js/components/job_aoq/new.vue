<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon, ExclamationTriangleIcon} from '@heroicons/vue/24/solid'
    import { onMounted, ref } from "vue"
	import { useRouter } from "vue-router";
	const router = useRouter();

	let jornolist=ref([]);
	let jor_no=ref([]);
	let rfqlist=ref([]);
	let jo_rfq_head_id=ref([]);
	let aoq_no=ref('');
	let head=ref([]);
	let vendors=ref([]);
	let signatories=ref([]);
	let date_needed=ref('');
	let received_by=ref('');
	let award_recommended_by=ref('');
	let recommended_by=ref('');
	let approved_by=ref(6);

	onMounted(async () => {
		getjorno()
	})

	const getjorno = async () => {
		let response = await axios.get("/api/jo_rfq_jor_list");
		jornolist.value=response.data
	}

	const UpdateRFQList = async () => {
		let jorno = jor_no.value
		if(jorno != ''){
			let response = await axios.get('/api/jo_rfq_list/'+jorno)
			rfqlist.value=response.data
			document.getElementById("select_rfq").disabled = false;
			document.getElementById("display_aoq_head").style.display="none"

			document.getElementById('dateneeded_').style.backgroundColor = '#FEFCE8';
			document.getElementById('awardrecommendedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById('recommendedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById('approvedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById("DateNeededAlert").style.display="none"
			document.getElementById("DropdownAlert").style.display="none"
		}else{
			document.getElementById("display_aoq_head").style.display="none"
			document.getElementById("select_rfq").disabled = true;
		}
	}

	const SelectBtn = async () => {
		if(jo_rfq_head_id.value != ''){
			document.getElementById("selectbtn").disabled = false;
		}else{
			document.getElementById("selectbtn").disabled = true;
			document.getElementById("display_aoq_head").style.display="none"
		}
	}

	const getAOQHeadDetails = async () => { 
		let jorrfqheadid = jo_rfq_head_id.value
		let response = await axios.get('/api/create_new_jo_aoq_details/'+jorrfqheadid)
		document.getElementById("display_aoq_head").style.display="block"
		aoq_no.value = response.data.aoq_no
		head.value = response.data.aoq_head_data
		vendors.value = response.data.rfq_vendor
		signatories.value=response.data.signatories
	}

	const allSelected=ref(false);
	const checkall=ref([]);
	const CheckAll = () => {
			var count_check=document.getElementsByClassName('checkboxes');
			for(var x=0;x<count_check.length;x++){
				var check=document.getElementsByClassName('checkboxes')[x].checked;
				if(!check){
					checkall.value=allSelected
					vendors.value[x].vendor_checkbox=1;
					document.getElementById("CreateAOQBtn").style.display="block"
				}else{
					vendors.value[x].vendor_checkbox=0;
					checkall.value=!allSelected
					document.getElementById("CreateAOQBtn").style.display="none"
				}
			}
	}

	const CountCheckbox= () =>{
		var isChecked = document.getElementsByClassName("checkboxes");
		var count=0;
		for(var x=0;x<isChecked.length;x++){
			if(isChecked[x].checked === true){
				count++;
			}
		}
			if(count>=1){
				document.getElementById("CreateAOQBtn").style.display="block"
			}else{
				document.getElementById("CreateAOQBtn").style.display="none"
			}
	}

	const CreateNewAOQAlert = () =>{
		if(date_needed.value == ''){
			document.getElementById('dateneeded_').style.backgroundColor = '#FAA0A0';
			document.getElementById("DateNeededAlert").style.display="block"
		}else if(recommended_by.value == ''){
			document.getElementById("DateNeededAlert").style.display="none"
			document.getElementById('dateneeded_').style.backgroundColor = '#FEFCE8';
			document.getElementById('approvedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById('recommendedby_').style.backgroundColor = '#FAA0A0';
			document.getElementById("DropdownAlert").style.display="block"
		}else if(approved_by.value == ''){
			document.getElementById("DateNeededAlert").style.display="none"
			document.getElementById('dateneeded_').style.backgroundColor = '#FEFCE8';
			document.getElementById('recommendedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById('approvedby_').style.backgroundColor = '#FAA0A0';
			document.getElementById("DropdownAlert").style.display="block"
		}else{
			document.getElementById('dateneeded_').style.backgroundColor = '#FEFCE8';
			document.getElementById('recommendedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById('approvedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById("DropdownAlert").style.display="none"
			document.getElementById("DateNeededAlert").style.display="none"
			CreateNewAOQModal.value = !CreateNewAOQModal.value
		}
	}
	
	const CreateNewAOQ= () =>{
		document.getElementById("YesCreate").disabled = true;
		document.getElementById("NoCreate").disabled = true;

		const formAOQHead= new FormData()
		formAOQHead.append('aoq_no', aoq_no.value)
		formAOQHead.append('jo_rfq_head_id', head.value.jo_rfq_head_id)
		formAOQHead.append('jor_no', head.value.jor_no)
		formAOQHead.append('aoq_date', head.value.aoq_date)
		formAOQHead.append('date_needed', date_needed.value)
		formAOQHead.append('prepared_by', head.value.prepared_by)
		formAOQHead.append('received_by', head.value.requestor_id)
		formAOQHead.append('award_recommended_by', award_recommended_by.value)
		formAOQHead.append('recommended_by', recommended_by.value)
		formAOQHead.append('approved_by', approved_by.value)
		formAOQHead.append('aoq_vendors', JSON.stringify(vendors.value))
			axios.post("/api/add_jo_aoq_head", formAOQHead).then(function (response) {
			router.push('/job_aoq/print_te/'+response.data)
			});
	}

	const showModal = ref(false)
	const hideModal = ref(true)
	const CreateNewAOQModal = ref(false)
	const openModel = () => {
		showModal.value = !showModal.value
	}
	const closeModal = () => {
		showModal.value = !hideModal.value
		CreateNewAOQModal.value = !hideModal.value
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
									<select class="form-control file-upload-info" v-model="jor_no" @change="UpdateRFQList">
										<option value="" disabled>-Select JOR-</option>
                                        <option :value="jor.jor_no" v-for="jor in jornolist" :key="jor.jor_no">{{ jor.jor_no }}</option>
                                    </select>
									<select class="form-control file-upload-info" id = 'select_rfq' v-model="jo_rfq_head_id" disabled @change="SelectBtn">
										<option value="">-Select RFQ-</option>
                                        <option :value="rfq.jo_rfq_head_id" v-for="rfq in rfqlist" :key="rfq.jo_rfq_head_id">{{ rfq.rfq_no }} (Total AOQ:{{ (rfq.count_aoq != '') ? rfq.count_aoq : 0  }})</option>
                                    </select>
									<span class="input-group-append">
										<!-- <button class="btn btn-primary" type="button" id="selectbtn" @click="pr_det = !pr_det" disabled >Select</button> -->
										<button class="btn btn-primary" type="button" id="selectbtn" disabled @click="getAOQHeadDetails()">Select</button>
									</span>
									</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div style="display:none" id="display_aoq_head">
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">JOR No: </span>
									<span class="text-sm text-gray-700">{{ head.jor_no }}</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">AOQ No: </span>
									<span class="text-sm text-gray-700">{{ aoq_no }}</span>
									<!-- <input type="text" class="form-control" placeholder="RFQ No" v-model="aoq_no" readonly> -->
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">{{ head.aoq_date}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">{{ head.department }}</span>
								</div>
								
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">RFQ No: </span>
									<span class="text-sm text-gray-700">{{ head.rfq_no }}</span>
								</div>
								
							</div>
							<div class="row">
								<!-- <div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use:</span>
									<span class="text-sm text-gray-700">{{ head.enduse }}</span>
								</div> -->
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Requested By: </span>
									<span class="text-sm text-gray-700">{{ head.requestor}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">{{ head.purpose }}</span>
								</div>
							</div>
							<input type="hidden" class="form-control" v-model="head.jor_no">
							<input type="hidden" class="form-control" v-model="head.jo_rfq_head_id">
							<br>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered text-sm" >
										<tr class="bg-gray-100">
											<td class="p-1" width="2%"><input type="checkbox" id="checkall" @click="CheckAll" :checked="allSelected"></td>
											<td class="p-1">List of Vendors</td>
										</tr>
										<tr class="bg-yellow-50" v-for="v in vendors">
											<td class="p-1"><input type="checkbox" class='checkboxes' v-model="v.vendor_checkbox" :checked="checkall" :true-value="1" :false-value="0" @change="CountCheckbox"></td>
											<td class="p-1">{{ v.vendor_name }} ({{v.vendor_identifier}})</td>
											<input type="hidden" class="form-control" v-model="v.jo_rfq_vendor_id">
										</tr>
									</table>
								</div>
							</div>
							<br>
							<hr>
							<div class="bg-red-100 border-2 border-red-200 w-full p-2 text-red-500 my-1 mb-2 hidden"  id="DateNeededAlert">
							<div class="flex justify-start space-x-2">
									<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ExclamationTriangleIcon>
									<span>Please fill in date needed.</span>
								</div>
							</div>
							<div class="bg-red-100 border-2 border-red-200 w-full p-2 text-red-500 my-1 mb-2 hidden"  id="DropdownAlert">
							<div class="flex justify-start space-x-2">
									<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ExclamationTriangleIcon>
									<span>Please select an option from the dropdown.</span>
								</div>
							</div>
							<div class="modal_s_items">
								<div class="row">
									<div class="col-lg-4 col-md-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Date Needed</label>
											<input type="text" class="p-2 border w-full bg-yellow-50 text-sm" onfocus="(this.type='date')" placeholder="Date Needed" id= "dateneeded_" v-model="date_needed">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Prepared by</label>
											<input type="text" class="form-control" v-model="head.prepared_by_name" readonly>
											<input type="hidden" class="form-control" v-model="head.prepared_by">
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Received and Checked by</label>
											<input type="text" class="form-control" v-model="head.requestor" readonly>
											<input type="hidden" class="form-control" v-model="head.requestor_id">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Award Recommended by</label>
											<select class="p-2 border w-full bg-yellow-50 text-sm" v-model="award_recommended_by" id= "awardrecommendedby_">
												<option value="">--Select Employee--</option>
												<option :value="s.id" v-for="s in signatories" :key="s.id">{{ s.name }}</option>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Recommending Approval</label>
											<select class="p-2 border w-full bg-yellow-50 text-sm" v-model="recommended_by" id= "recommendedby_">
												<option value="">--Select Employee--</option>
												<option :value="s.id" v-for="s in signatories" :key="s.id">{{ s.name }}</option>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label class="text-gray-500 m-0" for="">Approved by</label>
											<select class="p-2 border w-full bg-yellow-50 text-sm" v-model="approved_by" id= "approvedby_">
												<option value="">--Select Employee--</option>
												<option :value="s.id" v-for="s in signatories" :key="s.id">{{ s.name }}</option>
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
										<button type="submit" @click="CreateNewAOQAlert()" id="CreateAOQBtn" style="display:none" class="btn btn-primary mr-2 w-44">Create AOQ</button>
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
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:CreateNewAOQModal }">
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
									<h2 class="mb-2  font-bold text-green-400">Confirmation!</h2>
									<h5 class="leading-tight">Are you sure you want to save this new AOQ?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" id="NoCreate" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" id="YesCreate" @click="CreateNewAOQ()">Yes</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
	</navigation>
</template>