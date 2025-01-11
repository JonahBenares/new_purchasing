<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, ArrowUpOnSquareStackIcon, CheckIcon, EyeIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	import moment from 'moment'
	let error=ref('');
	let success=ref('');
	const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const successAlert = ref(false)
	const warningAlert = ref(false)
    const cancelAlert = ref(false)
	const hideAlert = ref(true)
	const modalRefered = ref(false)
	const viewComments = ref(false)
	let get_prhead=ref([]);
	let get_prdetails=ref([]);
	let prepared_by=ref('');
	let recommended_by=ref('');
	let approved_by=ref('');
	let prdetails_id=ref("");
	let referred_date=ref("");
	let comment=ref("");
	let pr_details_id_view=ref("")
	let pr_head_id_view=ref("")
	let cancel_reason=ref("")
	let referred_cancelled=ref([]);
	let referred_by=ref("")
	let cancelled_by=ref("")
	let cancelled_by_all=ref("")
	let remarks=ref("")
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
    onMounted(async () => {
		getPR()
	})
	const getPR = async () => {
		let response = await axios.get(`/api/get_view_details/${props.id}`);
		get_prhead.value=response.data.prhead
		cancelled_by_all.value=response.data.cancelled_by_all
		get_prdetails.value=response.data.prdetails
		remarks.value=response.data.comment
		prepared_by.value=response.data.prepared_by
		approved_by.value=response.data.approved_by
		recommended_by.value=response.data.recommended_by
	}
	const closeAlert = () => {
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert_item.value = !hideAlert.value
		cancelAlert.value = !hideAlert.value
	}
	const closeModal = () => {
		modalRefered.value = !hideAlert.value
		viewComments.value = !hideAlert.value
	}
	const printDiv = () => {
		window.print();
	}
	const openModalReferred = (id) => {
		modalRefered.value = !modalRefered.value
		prdetails_id.value=id
	}
	const openViewComments= async (prhead_id,prdetails_id) => {
		let response = await axios.get(`/api/referred_cancelled_data/`+prhead_id+'/'+prdetails_id);
		referred_cancelled.value=response.data.referred_cancelled
		referred_by.value=response.data.referred_by
		cancelled_by.value=response.data.cancelled_by
		viewComments.value = !viewComments.value
	}
	const insertRefered = (id) => {
		if(referred_date.value!='' && comment.value!=''){
			const formData= new FormData()
			formData.append('referred_date', referred_date.value)
			formData.append('referred_reason', comment.value)
			axios.post(`/api/insert_referred/${id}`,formData).then(function () {
				success.value='You have successfully referred the item!'
				successAlert.value = !successAlert.value
				referred_date.value=''
				comment.value=''
				document.getElementById('referredate_check').placeholder=""
				document.getElementById('referredate_check').style.backgroundColor = '#FFFFFF';
				document.getElementById('comment_check').placeholder=""
				document.getElementById('comment_check').style.backgroundColor = '#FFFFFF';
				closeModal()
				getPR()
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}, function (err) {
				error.value = err.response.data.message;
				dangerAlert.value = !dangerAlert.value
				closeModal()
				getPR()
			});
		}else{
			if(referred_date.value==''){
				document.getElementById('referredate_check').placeholder="Referred date must not be empty!"
				document.getElementById('referredate_check').style.backgroundColor = '#FAA0A0';
			}else if(referred_date.value!=''){
				document.getElementById('referredate_check').placeholder=""
				document.getElementById('referredate_check').style.backgroundColor = '#FFFFFF';
			}
			
			if(comment.value==''){
				document.getElementById('comment_check').placeholder="Comment must not be empty!"
				document.getElementById('comment_check').style.backgroundColor = '#FAA0A0';
			}else if(comment.value!=''){
				document.getElementById('comment_check').placeholder="Comment"
				document.getElementById('comment_check').style.backgroundColor = '#FFFFFF';
			}
		}
	}

	const updateRecomdate = (id) => {
		const formData= new FormData()
		formData.append('recom_date', JSON.stringify(get_prdetails.value))
		axios.post(`/api/update_recomdate_view`,formData).then(function (response) {
		}, function (err) {
			error.value = err.response.data.message;
		});
	}

	const cancelPrdetails = (option, id) => {
		if(option=='yes'){
			if(cancel_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_reason', cancel_reason.value)
				axios.post(`/api/cancel_prdetails/`+id,formData).then(function (response) {
					if(response.data!='error'){
						dangerAlert_item.value = !hideAlert.value
						success.value='Successfully cancelled item!'
						successAlert.value = !successAlert.value
						cancel_reason.value=''
						document.getElementById('cancel_check').placeholder=""
						document.getElementById('cancel_check').style.backgroundColor = '#FFFFFF';
						getPR()
						setTimeout(() => {
							closeAlert()
						}, 2000);
					}else{
						dangerAlert_item.value = !hideAlert.value
						success.value=''
						error.value='Cannot be deleted, item already have transactions.'
						cancel_reason.value=''
						cancelAlert.value = !cancelAlert.value
						getPR()
						setTimeout(() => {
							closeAlert()
						}, 2000);
					}
				})
			}else{
				document.getElementById('cancel_check').placeholder="Cancel Reason must not be empty!"
				document.getElementById('cancel_check').style.backgroundColor = '#FAA0A0';
			}
		}else{
			pr_details_id_view.value=id
			dangerAlert_item.value = !dangerAlert_item.value
		}
	}

	const cancelAllpr = (option) => {
		if(option=='yes'){
			axios.get(`/api/cancel_allpr/${props.id}`).then(function (response) {
				if(response.data!='error'){
					dangerAlert.value = !hideAlert.value
					success.value='Successfully cancelled PR!'
					successAlert.value = !successAlert.value
					getPR()
					setTimeout(() => {
						closeAlert()
					}, 2000);
				}else{
					dangerAlert.value = !hideAlert.value
					success.value=''
					error.value='Cannot be deleted, this PR already have transactions.'
					cancelAlert.value = !cancelAlert.value
					getPR()
					setTimeout(() => {
						closeAlert()
					}, 2000);
				}
			})
		}else{
			pr_head_id_view.value=props.id
			dangerAlert.value = !dangerAlert.value
		}
	}
</script>
<template>
	<navigation>
		
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Purchase Request <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_req">Purchase Request</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card print:h-screen">
					<div class="py-2 px-2 bg-red-500" v-if="get_prhead.status=='Cancelled'">
						<span class="font-bold text-white">CANCELLED || Cancelled By: {{ cancelled_by_all }}  || Cancelled Date: {{moment(get_prhead.cancelled_date).format('MMM. DD,YYYY')}} </span>
					</div>
					<div class="card-body">
						<div class="pt-1 " id="printable">
							<div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase">Purchase Request</span>
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
							<div class="print:block hidden print:flex print:justify-center h-full" v-if="get_prhead.status=='Cancelled'">
								<img src="../../../images/bg_cancelled.png" alt="" class="absolute h-[420px] align-center opacity-100">
							</div>
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Location: </span>
									<span class="text-sm text-gray-700">{{get_prhead.location}}</span>
								</div>
								<div class="col-lg-3 col-sm-3 col-md-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date Prepared: </span>
									<span class="text-sm text-gray-700">{{get_prhead.date_prepared}}</span>
								</div>
								<div class="col-lg-3 col-sm-3 col-md-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date Issued: </span>
									<span class="text-sm text-gray-700">{{get_prhead.date_issued}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<span class="text-sm text-gray-700 font-bold pr-1">PR Number: </span>
									<span class="text-sm text-gray-700">{{get_prhead.pr_no}}</span>
								</div>
								<div class="col-lg-6 col-sm-6 col-md-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Site PR Number: </span>
									<span class="text-sm text-gray-700">{{get_prhead.site_pr}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">{{get_prhead.department_name}}</span>
								</div>
								<div class="col-lg-4 col-sm-4 col-md-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
									<span class="text-sm text-gray-700">{{get_prhead.process_code}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
									<span class="text-sm text-gray-700">{{get_prhead.enduse}}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
									<span class="text-sm text-gray-700">{{get_prhead.urgency}}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">{{get_prhead.purpose}}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Requestor: </span>
									<span class="text-sm text-gray-700">{{get_prhead.requestor}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered bg-transparent !text-xs mt-3">
										<tr class="bg-gray-100 print:bg-transparent">
											<td class="p-1 uppercase text-center" width="5%">#</td>
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
											<td class="p-1 uppercase print:!bg-transparent print:hidden" width="15%">Recom Date</td>
											<td class="p-1 uppercase po_buttons" width="6%" align="center">
												<span>
													<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 " ></Bars3Icon>
												</span>
											</td>
										</tr>
										<tr v-for="(pd,index) in get_prdetails">
											<td :class="(pd.status=='Cancelled') ? 'bg-red-100 p-1 text-center print:!bg-transparent print:!text-red-500' : (pd.status=='Referred') ? 'bg-orange-200 p-1 text-center print:!bg-transparent print:!text-orange-500' : 'p-1 text-center'">{{index + 1}}</td>
											<td :class="(pd.status=='Cancelled') ? 'bg-red-100 p-1 text-center print:!bg-transparent print:!text-red-500' : (pd.status=='Referred') ? 'bg-orange-200 p-1 text-center print:!bg-transparent print:!text-orange-500' : 'p-1 text-center'">{{ pd.quantity }}</td>
											<td :class="(pd.status=='Cancelled') ? 'bg-red-100 p-1 text-center print:!bg-transparent print:!text-red-500' : (pd.status=='Referred') ? 'bg-orange-200 p-1 text-center print:!bg-transparent print:!text-orange-500' : 'p-1 text-center'">{{ pd.uom }}</td>
											<td :class="(pd.status=='Cancelled') ? 'p-1 bg-red-100 print:!bg-transparent print:!text-red-500' : (pd.status=='Referred') ? 'bg-orange-200 p-1 print:!bg-transparent print:!text-orange-500' : 'p-1'">{{ pd.pn_no }}</td>
											<td :class="(pd.status=='Cancelled') ? 'p-1 bg-red-100 print:!bg-transparent print:!text-red-500' : (pd.status=='Referred') ? 'bg-orange-200 p-1 print:!bg-transparent print:!text-orange-500' : 'p-1'">{{ pd.item_description }}</td>
											<td :class="(pd.status=='Cancelled') ? 'p-1 bg-red-100 print:!bg-transparent print:!text-red-500' : (pd.status=='Referred') ? 'bg-orange-200 p-1 print:!bg-transparent print:!text-orange-500' : 'p-1'">{{ pd.wh_stocks }}</td>
											<td :class="(pd.status=='Cancelled') ? 'p-1 bg-red-100 print:!bg-transparent print:!text-red-500' : (pd.status=='Referred') ? 'bg-orange-200 p-1 print:!bg-transparent print:!text-orange-500' : 'p-1'">{{ pd.date_needed }}</td>
											<td :class="(pd.status=='Cancelled') ? 'p-1 bg-red-100 print:!bg-transparent print:!text-red-500' : (pd.status=='Referred') ? 'bg-orange-200 p-1 print:!bg-transparent print:!text-orange-500' : 'p-1 print:!bg-transparent print:hidden'">
												<input type="date" :class=" (pd.recom_date==null) ? 'w-full bg-transparent print:hidden' : 'w-full bg-transparent'" v-model="pd.recom_date" @change="updateRecomdate(pd.id)"  v-if="pd.status!='Cancelled'">
												<span v-else>{{ pd.recom_date }}</span>
												<!-- <input type="date" class="w-full bg-transparent" v-model="pd.recom_date" @change="updateRecomdate(pd.id)" readonly v-else> -->
											</td>
											<td :class="(pd.status=='Cancelled') ? 'bg-red-100 text-center po_buttons p-0' : (pd.status=='Referred') ? 'bg-orange-200 text-center po_buttons p-0' : 'text-center po_buttons p-0'">
												<div class="space-x-1" v-if="pd.status=='Cancelled'">
													<button type="button" class="btn btn-xs btn-warning text-white p-1" @click="openViewComments(pd.pr_head_id,pd.id)">
														<EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></EyeIcon>
													</button>
												</div>
												<div class="space-x-1" v-else-if="pd.status=='Referred'">
													<button type="button" class="btn btn-xs btn-warning text-white p-1" @click="openViewComments(pd.pr_head_id,pd.id)">
														<EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></EyeIcon>
													</button>
													<button type="button" class="btn btn-xs btn-danger p-1" @click="cancelPrdetails('no',pd.id)">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></XMarkIcon>
													</button>
												</div>
												<div class="space-x-1" v-else-if="pd.status!='Referred'">
													<button class="btn btn-xs btn-info p-1" @click="openModalReferred(pd.id)" title="Refer">
														<ArrowUpOnSquareStackIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></ArrowUpOnSquareStackIcon>
													</button>
													<button type="button" class="btn btn-xs btn-danger p-1" @click="cancelPrdetails('no',pd.id)">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></XMarkIcon>
													</button>
												</div>
											</td>
										</tr>
										<tr v-if="get_prhead.petty_cash=='1'">
											<td colspan="9">
												<div class="flex justify-start space-x-1">
													<span class="p-1">Comment: {{ remarks }}</span>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<hr class="border-dashed">
							<div class="row mt-4 mb-4" v-if="get_prhead.petty_cash=='1'">
								<div class="col-lg-12">
									<table class="w-full text-xs">
										<tr>
											<td class="text-center" width="20%">Prepared by</td>
											<td width="2%"></td>
											<td class="text-center" width="20%">Recommending Approval</td>
											<td width="2%"></td>
											<td class="text-center" width="20%">Approved by</td>
										</tr>
										<tr>
											<td class="text-center border-b"><br></td>
											<td></td>
											<td class="text-center border-b"></td>
											<td></td>
											<td class="text-center border-b"></td>
										</tr>
										<tr>
											<td class="text-center p-0">{{ prepared_by }}</td>
											<td></td>
											<td class="text-center p-0">{{ recommended_by }}</td>
											<td></td>
											<td class="text-center p-0">{{ approved_by }}</td>
										</tr>
										<tr>
											<td class="text-center"><br><br></td>
											<td></td>
											<td class="text-center"></td>
											<td></td>
											<td class="text-center"></td>
										</tr>
									</table>
								</div>
							</div>
							<div class="row my-2 po_buttons" > 
								<div class="col-lg-12 col-md-12">
									<div class="flex justify-center space-x-2">
										<button type="button" class="btn btn-danger mr-2 w-36" @click="cancelAllpr('no')" v-if="get_prhead.status!='Cancelled' && get_prhead.status!='Closed'">Cancel</button>
										<button type="button" class="btn btn-primary mr-2 w-36" @click="printDiv()">Print</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlert }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">{{ success }}</h5>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">Are you sure you want to cancel this PR?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="cancelAllpr('yes')">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:cancelAlert }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Error!</h2>
									<h5 class="leading-tight">{{ error }}</h5>
								</div>
							</div>
						</div>
						<!-- <br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeAlert()">Yes</button>
								</div>
							</div>
						</div> -->
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">
										Are you sure you want to cancel this item?<br>
										If yes, please state your reason.
									</h5>
									<label>Cancel Reason: </label>
									<textarea name="" id="cancel_check" class="form-control !border" rows="3" v-model="cancel_reason"></textarea>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"   @click="cancelPrdetails('yes',pr_details_id_view)">Yes</button>
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
			<div class="modal pt-4 px-3" :class="{ show:modalRefered }">
				<div @click="closeModal()" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Referred</span>
							<a href="#" class="text-gray-600" @click="closeModal()">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Referred Date</label>
									<input type="date" id="referredate_check" class="form-control" placeholder="Reffered Date" v-model="referred_date">
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Comment</label>
									<textarea class="form-control" id="comment_check" placeholder="Comment" rows="3" v-model="comment"></textarea>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="insertRefered(prdetails_id)" class="btn btn-primary mr-2 w-44">Save</button>
									<!-- <a href="" class="btn btn-primary mr-2 w-44">Save</a> -->
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
			<div class="modal pt-4 px-3" :class="{ show:viewComments }">
				<div @click="closeModal()" class="w-full h-full fixed"></div>
				<!-- Reffered here -->
				
				<div class="modal__content w-6/12" v-if="(referred_cancelled.referred_by!=null && referred_cancelled.referred_by!='') || referred_cancelled.status!='Cancelled'">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold text-orange-500">Referred</span>
							<a href="#" class="text-gray-600" @click="closeModal()">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="flex justify-start space-x-1">
									<label class="text-gray-500 m-0 text-sm" for="">Referred Date: {{moment(referred_cancelled.referred_date).format('MMM. DD, YYYY')}}</label>
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Comment:</label>
									<p>{{referred_cancelled.referred_reason}}</p>
								</div>
								<div class="form-group">
									<label class="text-gray-500 text-sm" for="">Referred By: {{referred_by}}</label>
								</div>
							</div>
						</div>
					</div> 
				</div>
				<!-- Reffered here -->
				 <!-- cancel here -->
				<div class="modal__content w-6/12" v-if="referred_cancelled.status=='Cancelled'">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold text-red-500">Cancelled</span>
							<a href="#" class="text-gray-600" @click="closeModal()">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="flex justify-start space-x-1">
									<label class="text-gray-500 m-0 text-sm" for="">Date Cancelled: {{moment(referred_cancelled.cancelled_date).format('MMM. DD, YYYY')}}</label>
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Cancel Reason:</label>
									<p>{{referred_cancelled.cancelled_reason}}</p>
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0 text-sm" for="">Cancelled By: {{cancelled_by}}</label>
								</div>
							</div>
						</div>
					</div> 
				</div>
				 <!-- cancel here -->
			</div>
		</Transition>
	</navigation>
</template>