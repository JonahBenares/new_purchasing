<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, Bars4Icon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();
    const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const drawer_dr = ref(false)
	const drawer_rfd = ref(false)
	const drawer_revise = ref(false)
	const hideModal = ref(true)
	const hideAlert = ref(true)
    const openDangerPO = () => {
		dangerAlert.value = !dangerAlert.value
	}
    const openDangerItem = () => {
		dangerAlert_item.value = !dangerAlert_item.value
	}
    const closeAlert = () => {
		dangerAlert_item.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
	}
	const openDrawerDR = () => {
		drawer_dr.value = !drawer_dr.value
	}
    const openDrawerRFD = () => {
		drawer_rfd.value = !drawer_rfd.value
	}
    const openDrawerRevise = () => {
		drawer_revise.value = !drawer_revise.value
	}
	const closeModal = () => {
		drawer_dr.value = !hideModal.value
		drawer_rfd.value = !hideModal.value
		drawer_revise.value = !hideModal.value
	}
    const printDiv = () => {
		window.print();
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
                            <span class="font-bold text-gray-500 text-base">ACKNOWLEDGEMENT RECEIPT</span>
                        </div>
                        <hr class="border-dashed mt-0">
                        <div class="pt-1" id="printable">
                            <div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase">ACKNOWLEDGEMENT RECEIPT</span>
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
                            <div>
								<div class="row">
									<div class="col-lg-8 col-sm-8 col-md-8">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">DR No: </span>
											<input type="text" class="border-b bg-white w-full font-bold text-sm"  disabled>
										</div>
									</div>
									<div class="col-lg-4 col-sm-4 col-md-4">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-36">Date: </span>
											<input type="text" class="border-b bg-white w-full text-sm" disabled>
										</div>	
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8 col-sm-8 col-md-8">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">Delivered to: </span>
											<textarea class="border-b bg-yellow-50 w-full text-sm" rows="1"></textarea>
										</div>
									</div>
									<div class="col-lg-4 col-sm-4 col-md-4">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-36">Gatepass No: </span>
											<input type="text" class="border-b bg-yellow-50 w-full text-sm" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8 col-sm-8 col-md-8">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">Address: </span>
											<input type="text" class="border-b bg-yellow-50 w-full text-sm" >
										</div>
									</div>
									<div class="col-lg-4 col-sm-4 col-md-4">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-36">JO No: </span>
											<input type="text" class="border-b bg-white w-full text-sm" disabled>
										</div>
									</div>
								</div>
                                <div class="row">
									<div class="col-lg-8 col-sm-8 col-md-8">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">Requested By: </span>
											<input type="text" class="border-b bg-yellow-50 w-full text-sm" >
										</div>
									</div>
									<!-- <div class="col-lg-4 col-sm-4 col-md-4">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-36">JO No: </span>
											<input type="text" class="border-b bg-white w-full text-sm" disabled>
										</div>
									</div> -->
								</div>
								<div class="" >
									<br>
									<div class="row">
										<div class="col-lg-12">
											<div class="border-2">
												<table class="table-bordered w-full !text-xs">
													<tr class="!border-b-3">
														<td colspan="7" class="py-2">
															<p class="text-sm font-bold text-gray-600 text-center m-0">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</p>
															<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
														</td>
													</tr>
													<tr class="bg-gray-100">
														<td class="uppercase p-1 text-center" width="1%">#</td>
														<td class="uppercase p-1" width="20%">Supplier </td>
														<td class="uppercase p-1" width="20%">Description</td>
														<td class="uppercase p-1 text-center" width="5%">DLVRD</td>
														<td class="uppercase p-1 text-center" width="5%">RCVD</td>
														<td class="uppercase p-1 text-center" width="5%">UOM</td>
														<td class="uppercase p-1 text-center" width="12%">Remarks</td>
													</tr>
													<!-- <tr class="">
														<td class="border-y-none p-1" colspan="3">
                                                            <div class="flex justify-between space-x-2">
                                                                <div class="w-full">
                                                                    <span class="font-bold">Supply of manpower/labor, laboratory tools/equipment, and
                                                                    technical expertise for the following:</span>
                                                                    <br>1. 1. Standard governor overhauling/dismantling, cleaning and replacement of parts as seen necessary (i.e. gaskets, bearings, o-rings, etc.)
                                                                    <br>2. Inspection and checking of all parts for wear, cracks, corrosion and other damages.
                                                                    <br>3. Repair and replacement of parts as seen upon inspection.
                                                                    <br>4. Setting of internal parts and mounting of the governor.
                                                                    <br>5. Calibration and bench testing for:
                                                                    <br>5.1. Speed Setting and Indicator
                                                                    <br>5.2. Speed Droop Setting and Indicator
                                                                    <br>5.3. Load Limit Setting and Indicator
                                                                    <br>6. Functional test of shut-down solenoid valve
                                                                    <br>7. Testing and Commissioning
                                                                    <br>8. Submission of inspection, service, commissioning and bench testing reports.
                                                                    <br>9. Other works necessary for job completion.
                                                                </div>
                                                                <a @click="openDangerItem()" class="!text-red-500 cursor-pointer po_buttons">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a>
                                                            </div>
															
														</td>
														<td class="border-y-none p-1 text-center">
                                                            <input type="text" class="w-full text-center" value="5" placeholder="00">
                                                        </td>
														<td class="border-y-none p-1 text-center">pc</td>
														<td class="border-y-none p-1 text-right">100.00</td>
														<td class="border-y-none p-1 text-right">500.00</td>
													</tr>
													 -->
													<tr class="">
														<td class="p-1 text-center">1</td>
														<td class="p-1 "></td>
														<td class="p-1 "></td>
														<td class="p-1 text-center">23</td>
														<td class="p-1 text-center">23</td>
														<td class="p-1 "></td>
														<td class="p-1 "></td>
													</tr>
												</table>
											</div>
										</div>
									</div>
                                    
                                    <div class="row mt-4 mb-4">
                                        <div class="col-lg-12">
                                            <table class="w-full text-xs">
                                                <tr>
                                                    <td class="text-center" width="20%">Prepared by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Received by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Noted by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Witnessed by</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center border-b"><br></td>
                                                    <td></td>
                                                    <td class="text-center border-b"></td>
                                                    <td></td>
                                                    <td class="text-center border-b"></td>
                                                    <td></td>
                                                    <td class="text-center border-b"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center p-1">Henne Tanant</td>
                                                    <td></td>
                                                    <td class="text-center p-1">Beverly Sy</td>
                                                    <td></td>
                                                    <td class="text-center p-1">Jonah Marie Dy</td>
                                                    <td></td>
                                                    <td class="text-center p-1">Glenn Paulate</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <hr	class="border-dashed">
                                    
                                    <div class="row my-2 po_buttons" > 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2">
                                                <div class="flex justify-start space-x-1">
                                                    <a href="#" class="btn btn-primary" onclick="close_window();return false;">Back</a>
                                                    <div class="flex justify-between">
                                                        <a href="#" @click="printDiv()" type="submit" class="btn btn-warning !text-white w-26">Save & Print</a>
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
			</div>
		</div>
	</navigation>
</template>