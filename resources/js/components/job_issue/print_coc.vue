<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, Bars4Icon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
     import moment from 'moment'
	const preview =  ref();
	const success =  ref('');
	const error =  ref('');
    const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const drawer_dr = ref(false)
	const drawer_rfd = ref(false)
	const drawer_revise = ref(false)
	const hideModal = ref(true)
	const hideAlert = ref(true)
    const successAlertCD = ref(false)
    const dangerAlerterrors = ref(false)
    const coc_no =  ref('');
    const vendor =  ref([]);
	const jor_head = ref([])
	const joi_coc = ref([])
	const joi_head = ref([])
	const joi_labors = ref([])
	const joi_materials = ref([])
	const signatories = ref([])
    const date = ref(moment().format('MMMM DD, YYYY'))
    const date2 = ref(moment().format('YYYY-MM-DD'))
    const warranty = ref("One (1) year warranty for parts and three (3) months warranty for service.")
    const checked_by=ref(0)
    const approved_by=ref(0)
    const checked_position=ref("")
    const approved_position=ref("")
    const saved = ref(0)
    const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		getCocData()
        getSignatories()
	})
    const getCocData = async () => {
		let response = await axios.get("/api/get_cocdata/"+props.id);
		coc_no.value = response.data.coc_no;
        if(response.data.joi_coc!=null){
		    joi_coc.value = response.data.joi_coc;
            coc_no.value=joi_coc.value.coc_no
            date2.value=joi_coc.value.date_prepared
            warranty.value=joi_coc.value.warranty
            checked_by.value=joi_coc.value.checked_by
            approved_by.value=joi_coc.value.approved_by
            saved.value=joi_coc.value.saved
            chooseEmpchecked(checked_by.value)
            chooseEmpapproved(approved_by.value)
        }
		jor_head.value = response.data.jor_head;
		joi_head.value = response.data.joi_head;
		joi_labors.value = response.data.joi_labors;
		joi_materials.value = response.data.joi_materials;
		vendor.value = response.data.vendor;
	}

    const chooseEmpchecked = async (checked_by) => {
        let response = await axios.get("/api/get_checkposition/"+checked_by);
        checked_position.value = response.data.position;
    }

    const chooseEmpapproved = async (approved_by) => {
        let response = await axios.get("/api/get_approveposition/"+approved_by);
        approved_position.value = response.data.position;
    }

    const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
	}

    const onSave = () => {
		const formData= new FormData()
		formData.append('coc_no', coc_no.value)
		formData.append('warranty', warranty.value)
		formData.append('date_prepared', date2.value)
		formData.append('checked_by', checked_by.value)
		formData.append('approved_by', approved_by.value)
		formData.append('joi_head_id', props.id)
        if(checked_by.value!=0 && approved_by.value!=0){
            axios.post(`/api/save_coc`,formData).then(function (response) {
                success.value='You have successfully saved new COC.'
                successAlertCD.value=!successAlertCD.value
                setTimeout(() => {
                    getCocData()
                    successAlertCD.value=!hideAlert.value
                }, 2000);
            }, function (err) {
                error.value='Error! Please try again.';
                dangerAlerterrors.value=!dangerAlerterrors.value
            });
        } else{
            if(checked_by.value==0){
                document.getElementById('checked_by').style.backgroundColor = '#FAA0A0';
            }
            if(approved_by.value==0){
                document.getElementById('approved_by').style.backgroundColor = '#FAA0A0';
            }
            const btn_save = document.getElementById("save");
            btn_save.disabled = true;
        }
    }
	const closeAlert = () => {
		successAlertCD.value = !hideAlert.value
        dangerAlerterrors.value = !hideAlert.value
	}
    const printDiv = () => {
		window.print();
	}
    const resetError = (button) => {
		if(button==='button1'){
			document.getElementById('checked_by').style.backgroundColor = '#FEFCE8';
		}
		if(button==='button2'){
			document.getElementById('approved_by').style.backgroundColor = '#FEFCE8';
		}
		const btn_save = document.getElementById("save");
		btn_save.disabled = false;
	}
</script>
<template>
    <navigation>
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Issuance <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_issue">JO Issuance</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
                    <div class="card-body">
                        <div class="print:hidden mb-2">
                            <span class="font-bold text-gray-500 text-base">CERTIFICATE OF COMPLETION</span>
                        </div>
                        <hr class="border-dashed mt-0">
                        <div class="pt-1" id="printable">
                            <div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase">CERTIFICATE OF COMPLETION</span>
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
                            <div>
                                
								<div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date: </span>
                                            <input type="text" class="border-b bg-white w-full" v-model="date" disabled>
										</div>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-20 text-right">Ref: </span>
											<input type="text" class="border-b bg-white w-full" v-model="coc_no" disabled>
										</div>	
									</div>
								</div>
								
                                <div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">JO Reference: </span>
                                            <span hidden>
                                                {{ joi_no = joi_head.joi_no}} {{ (joi_head.revision_no!=0 && joi_head.revision_no!=null) ?  revision_no = '.r'+joi_head.revision_no : revision_no = '' }}
                                                {{ jo_no = joi_no+revision_no  }}
                                            </span>
											<input type="text" class="border-b bg-white w-full" v-model="jo_no" disabled>
										</div>
									</div>
								</div>
                                
                                <div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">Activity: </span>
											<input type="text" class="border-b bg-white w-full" v-model="jor_head.project_activity" disabled>
										</div>
									</div>
                                </div>
                                <br>
								<div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p>	This is to certify that <span class="text-sm text-gray-700 font-bold pr-1 !w-40">{{ vendor.vendor_name }} {{ '('+vendor.identifier+')' }}</span>has already completed the following scope of works for:</p>
										</div>
									</div>
								</div>
								<div class="row px-5">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p class="text-sm">
                                                A. Supply of labor, Materials, tools, equipment and technical expertise for the <span class="text-sm text-gray-700 font-bold pr-1 !w-40">{{ jor_head.project_activity }}</span><br>
                                                <br>
                                                Scope of work includes:<br><br>

                                                <template v-for="jl in joi_labors">
                                                    <span class="w-full">{{ jl.item_description }}</span>
                                                </template>
                                            </p>
										</div>
									</div>
								</div>
                                <div class="row px-5">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p class="text-sm">
                                                Materials:<br><br>
                                                <template v-for="jm in joi_materials">
                                                    <span class="w-full">{{ jm.item_description }}</span>
                                                </template>
                                            </p>
										</div>
									</div>
								</div>
                                <div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p>The above scope of works was completed and tested by <span class="text-sm text-gray-700 font-bold pr-1 !w-40">{{ vendor.vendor_name }} {{ '('+vendor.identifier+')' }}</span> on <input type="date" class="border px-2" v-model="date2" v-if="saved==0"><input type="date" class="border px-2" v-model="date2" v-else readonly> </p>
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<textarea name="" class="w-full border text-sm px-2 py-1" id="" cols="30" rows="2" v-model="warranty" v-if="saved==0"></textarea>
										<textarea name="" class="w-full border text-sm px-2 py-1" id="" cols="30" rows="2" v-model="warranty" v-else readonly></textarea>
									</div>
								</div>
                                <div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p class="text-sm">This certification is being issued on the above-name contractor for payment purposes only.</p>
										</div>
									</div>
								</div>
								<div class="" >
                                    <div class="row mt-4 mb-4">
                                        <div class="col-lg-12">
                                            <table class="w-full text-sm">
                                                <tr>
                                                    <td class="text-left" width="20%">Check and Endorsed by:</td>
                                                    <td width="2%"></td>
                                                    <td class="text-left" width="20%">Approve by:</td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="text-center p-1">
                                                        <select class="py-1  w-full bg-yellow-50" v-model="checked_by" id="checked_by" @change="chooseEmpchecked(checked_by)" v-if="saved==0" @click="resetError('button1')">
															<option value='0'>--Select Checked by & Endorsed by--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
                                                        <select class="py-1  w-full" v-model="checked_by" id="checked_by" @change="chooseEmpchecked(checked_by)" v-else style="pointer-events: none;-webkit-appearance: none;-moz-appearance: none;">
															<option value='0'>--Select Checked by & Endorsed by--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
                                                    </td>
                                                    <td></td>
                                                    <td class="text-center p-1">
                                                        <select class="py-1  w-full bg-yellow-50" v-model="approved_by" id="approved_by" @change="chooseEmpapproved(approved_by)" v-if="saved==0" @click="resetError('button2')">
															<option value='0'>--Select Approved by--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
                                                        <select class="py-1  w-full" v-model="approved_by" id="approved_by" @change="chooseEmpapproved(approved_by)" v-else style="pointer-events: none;-webkit-appearance: none;-moz-appearance: none;">
															<option value='0'>--Select Approved by--</option>
															<option :value="sig.id" v-for="sig in signatories" :key="sig.id">{{ sig.name }}</option>
														</select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left border-b">{{ checked_position }}</td>
                                                    <td></td>
                                                    <td class="text-left border-b">{{ approved_position }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <hr	class="border-dashed">
                                    
                                    <div class="row my-2 po_buttons" > 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2">
                                                <button type="button" class="btn btn-primary text-white" @click="onSave()" id="save" v-if="saved==0">Save</button>
                                                <button type="button" class="btn btn-primary text-white" @click="printDiv()" v-else>Print COC</button>
                                            </div>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlertCD }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlerterrors }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight" v-if="error!=''" >{{ error }}</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeAlert()">Close</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>