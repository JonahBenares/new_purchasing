<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const showModal = ref(false)
	const hideModal = ref(true)
	const openModel = () => {
		showModal.value = !showModal.value
	}
	const closeModal = () => {
		showModal.value = !hideModal.value
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Quotation <small>Print</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_quote">JO Request for Quotation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Print</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					
					<div class="pt-1">
						<div id="details">
							<hr class="border-dashed mt-2">
							<div class="row">
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-6">
											<span class="text-sm text-gray-700 font-bold pr-1">RFQ No: </span>
											<span class="text-sm text-gray-700">RFQ-CENPRI-1001</span>
										</div>
										<div class="col-lg-6">
											<span class="text-sm text-gray-700 font-bold pr-1">Date:</span>
											<span class="text-sm text-gray-700">February 16, 2024</span>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<span class="text-sm text-gray-700 font-bold pr-1">RFQ Name: </span>
											<span class="text-sm text-gray-700">rfq_it001</span>
										</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="flex justify-end">
										<a href="/job_quote/view" class="btn btn-sm p-1 px-3 !text-xs btn-primary">Encode For TE</a>
									</div>
								</div>
							</div>
							<br>
						</div>
						<div v-if="vendor === 'vendor_1'">
							<div class="rfq_buttons">
								<div class="w-full flex justify-between space-x-1">
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
									<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
									<button @click="openModel()" class="btn btn-primary p-1">
										<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
									</button>
								</div>
							</div>
							<div class="page-bg">
								<div class="page">
									<div class="subpage">
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
										<table class="table-bosrdered w-full text-xs mb-0">
											<tr>
												<td class="" width="10%">Date: </td>
												<td class="">June 28, 2024</td>
												<td class="" width="10%">RFQ No.:</td>
												<td class="" >RFQ-CENPRI-1001</td>
											</tr>
											<tr>
												<td class="">JOR No:</td>
												<td class="" width="50%">JOR-CENPRI24-1002</td>
												<td class="" >RFQ Name:</td>
												<td class="" >rfq_it001</td>
											</tr>
										</table>
										<hr class="border-dashed my-2">
										<table class="table-bsordered w-full text-xs mb-2">
											<tr>
												<td class="" width="10%">Supplier: </td>
												<td class="" colspan="4">MF Computer Solutions, Inc.</td>
											</tr>
											<tr>
												<td class="" width="10%">Contact No.:</td>
												<td class="" width="40%">(034) 433-8370</td>
												<td></td>
												<td class="" width="15%">Duration: </td>
												<td class="border-b" width="">asd</td>
											</tr>
											<tr>
												<td class="">Warranty: </td>
												<td class="border-b"></td>
												<td></td>
												<td class="">Payment Terms:</td>
												<td class="border-b" ></td>
											</tr>
											<tr>
												<td class="">Notes: </td>
												<td class="border-b" colspan="4"></td>
											</tr>
										</table>
										<div class="border-y-2 py-1 mb-2">
											<p class="text-sm font-bold text-gray-600 text-center m-0">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</p>
											<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
										</div>
										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="50%">Scope of Work</td>
												<td class="p-1" width="35%">Offer</td>
												<td class="p-1 text-center" width="15%">Unit Price</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Supply of manpower/labor, tools, equipment and technical expertise for the following:
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
												</td>
												<td class="align-top"></td>
												<td class="align-top"></td>
											</tr>
										</table>
										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1 text-center" width="5%">No</td>
												<td class="p-1 text-center" width="10%">Qty</td>
												<td class="p-1" width="35%">Item Description</td>
												<td class="p-1" width="35%">Brand/Offer</td>
												<td class="p-1 text-center" width="15%">Unit Price</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Monitor</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="p-1 w-full h-10 !align-top"></div>
												</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="p-1 w-full h-10 !align-top text-center"></div>
												</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Mouse</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class=" p-1 w-full h-10 !align-top"></div>
												</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class=" p-1 w-full h-10 !align-top text-center"></div>
												</td>
											</tr>
										</table>
										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="">notes</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Governor Specifications: Make: Woodward Model: UG 40 Governor RPM: 487 - 1120 Designation Number: G8530 410 Serial Number: 1178418
												</td>
											</tr>
										</table>
										<br>
										<br>
										<table class="w-full text-xs">
											<tr>
												<td class="text-center" width="30%">Prepared by</td>
												<td width="5%"></td>
												<td class="text-center" width="30%">Noted by</td>
												<td width="5%"></td>
												<td class="text-center" width="30%">Approved by</td>
											</tr>
											<tr>
												<td class="text-center border-b"><br></td>
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
								<div class="row my-2 po_buttons"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-center space-x-2">
											<button type="submit" class="btn btn-primary mr-2 w-44"  @click="printDiv()">Print</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div v-else-if="vendor === 'vendor_2'">
							<div class="rfq_buttons">
								<div class="w-full flex justify-between space-x-1">
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
									<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
									<button @click="openModel()" class="btn btn-primary p-1">
										<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
									</button>
								</div>
							</div>
							<div class="page-bg">
								<div class="page">
									<div class="subpage">
										<table class="table-bosrdered w-full text-xs mb-0">
											<tr>
												<td class="" width="10%">Date: </td>
												<td class="">June 28, 2024</td>
												<td class="" width="10%">RFQ No.:</td>
												<td class="" >RFQ-CENPRI-1001</td>
											</tr>
											<tr>
												<td class="">JOR No:</td>
												<td class="" width="50%">JOR-CENPRI24-1002</td>
												<td class="" >RFQ Name:</td>
												<td class="" >rfq_it001</td>
											</tr>
										</table>
										<hr class="border-dashed my-2">
										<table class="table-bsordered w-full text-xs mb-2">
											<tr>
												<td class="" width="10%">Supplier: </td>
												<td class="" colspan="4">Nexus Industrial Prime Solutions Corp.</td>
											</tr>
											<tr>
												<td class="" width="10%">Contact No.:</td>
												<td class="" width="40%">(034) 433-8370</td>
												<td></td>
												<td class="" width="15%">Duration: </td>
												<td class="border-b" width="">asd</td>
											</tr>
											<tr>
												<td class="">Warranty: </td>
												<td class="border-b"></td>
												<td></td>
												<td class="">Payment Terms:</td>
												<td class="border-b" ></td>
											</tr>
											<tr>
												<td class="">Notes: </td>
												<td class="border-b" colspan="4"></td>
											</tr>
										</table>
										<div class="border-y-2 py-1 mb-2">
											<p class="text-sm font-bold text-gray-600 text-center m-0">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</p>
											<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
										</div>
										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="50%">Scope of Work</td>
												<td class="p-1" width="35%">Offer</td>
												<td class="p-1 text-center" width="15%">Unit Price</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Supply of manpower/labor, tools, equipment and technical expertise for the following:
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
												</td>
												<td class="align-top"></td>
												<td class="align-top"></td>
											</tr>
										</table>
										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1 text-center" width="5%">No</td>
												<td class="p-1 text-center" width="10%">Qty</td>
												<td class="p-1" width="35%">Item Description</td>
												<td class="p-1" width="35%">Brand/Offer</td>
												<td class="p-1 text-center" width="15%">Unit Price</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Monitor</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="p-1 w-full h-10 !align-top"></div>
												</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="p-1 w-full h-10 !align-top text-center"></div>
												</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Mouse</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class=" p-1 w-full h-10 !align-top"></div>
												</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class=" p-1 w-full h-10 !align-top text-center"></div>
												</td>
											</tr>
										</table>
										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="">notes</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Governor Specifications: Make: Woodward Model: UG 40 Governor RPM: 487 - 1120 Designation Number: G8530 410 Serial Number: 1178418
												</td>
											</tr>
										</table>
										<br>
										<br>
										<table class="w-full text-xs">
											<tr>
												<td class="text-center" width="30%">Prepared by</td>
												<td width="5%"></td>
												<td class="text-center" width="30%">Noted by</td>
												<td width="5%"></td>
												<td class="text-center" width="30%">Approved by</td>
											</tr>
											<tr>
												<td class="text-center border-b"><br></td>
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
								<div class="row my-2 po_buttons"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-center space-x-2">
											<button type="submit" class="btn btn-primary mr-2 w-44"  @click="printDiv()">Print</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div v-else>
							<div class="rfq_buttons">
								<div class="w-full flex justify-between space-x-1">
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
									<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
									<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
									<button @click="openModel()" class="btn btn-primary p-1">
										<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
									</button>
								</div>
							</div>
							<div class="page-bg">
								<div class="page">
									<div class="subpage">
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
										<table class="table-bosrdered w-full text-xs mb-0">
											<tr>
												<td class="" width="10%">Date: </td>
												<td class="">June 28, 2024</td>
												<td class="" width="10%">RFQ No.:</td>
												<td class="" >RFQ-CENPRI-1001</td>
											</tr>
											<tr>
												<td class="">JOR No:</td>
												<td class="" width="50%">JOR-CENPRI24-1002</td>
												<td class="" >RFQ Name:</td>
												<td class="" >rfq_it001</td>
											</tr>
										</table>
										<hr class="border-dashed my-2">
										<table class="table-bsordered w-full text-xs mb-2">
											<tr>
												<td class="" width="10%">Supplier: </td>
												<td class="" colspan="4">Lectrix Solutions Electrical Supplies & Services Cebu</td>
											</tr>
											<tr>
												<td class="" width="10%">Contact No.:</td>
												<td class="" width="40%">(034) 433-8370</td>
												<td></td>
												<td class="" width="15%">Duration: </td>
												<td class="border-b" width="">asd</td>
											</tr>
											<tr>
												<td class="">Warranty: </td>
												<td class="border-b"></td>
												<td></td>
												<td class="">Payment Terms:</td>
												<td class="border-b" ></td>
											</tr>
											<tr>
												<td class="">Notes: </td>
												<td class="border-b" colspan="4"></td>
											</tr>
										</table>
										<div class="border-y-2 py-1 mb-2">
											<p class="text-sm font-bold text-gray-600 text-center m-0">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</p>
											<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
										</div>
										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="50%">Scope of Work</td>
												<td class="p-1" width="35%">Offer</td>
												<td class="p-1 text-center" width="15%">Unit Price</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Supply of manpower/labor, tools, equipment and technical expertise for the following:
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
												</td>
												<td class="align-top"></td>
												<td class="align-top"></td>
											</tr>
										</table>
										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1 text-center" width="5%">No</td>
												<td class="p-1 text-center" width="10%">Qty</td>
												<td class="p-1" width="35%">Item Description</td>
												<td class="p-1" width="35%">Brand/Offer</td>
												<td class="p-1 text-center" width="15%">Unit Price</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Monitor</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="p-1 w-full h-10 !align-top"></div>
												</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="p-1 w-full h-10 !align-top text-center"></div>
												</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Mouse</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class="border-b p-1 w-full h-10 !align-top"></div>
													<div class=" p-1 w-full h-10 !align-top"></div>
												</td>
												<td class="align-top">
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
													<div class=" p-1 w-full h-10 !align-top text-center"></div>
												</td>
											</tr>
										</table>

										<table class="table-bordered w-full text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="">notes</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Governor Specifications: Make: Woodward Model: UG 40 Governor RPM: 487 - 1120 Designation Number: G8530 410 Serial Number: 1178418
												</td>
											</tr>
										</table>
										
										<br>
										<br>
										<table class="w-full text-xs">
											<tr>
												<td class="text-center" width="30%">Prepared by</td>
												<td width="5%"></td>
												<td class="text-center" width="30%">Noted by</td>
												<td width="5%"></td>
												<td class="text-center" width="30%">Approved by</td>
											</tr>
											<tr>
												<td class="text-center border-b"><br></td>
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
								<div class="row my-2 po_buttons"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-center space-x-2">
											<button type="submit" class="btn btn-primary mr-2 w-44"  @click="printDiv()">Print</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- <br>
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-primary mr-2 w-44">Print</button>
								</div>
							</div>
						</div> -->
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
									<label class="text-gray-500 m-0" for="">Vendor</label>
									<select type="text" class="form-control" placeholder="Vendor" value="">
										<option value="">Select Vendor</option>
										<option value="">Tough Performance AutoWorkz</option>
										<option value="">2GO Express, Inc.</option>
										<option value="">4 Sisters Sack's Trading</option>
										<option value="">A-1 Gas Corporation</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/pur_quote/print" class="btn btn-primary mr-2 w-44">Save</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>