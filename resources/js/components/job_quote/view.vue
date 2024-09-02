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
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Quotation <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_quote">JO Request for Quotation</a></li>
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
					
					<hr class="border-dashed mt-2">
					<div class="pt-1">
						<div class="row">
							<div class="col-lg-4">
								<span class="text-sm text-gray-700 font-bold pr-1">RFQ No: </span>
								<span class="text-sm text-gray-700">RFQ-CENPRI-1001</span>
							</div>
							<div class="col-lg-4">
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
						<br>
						<div v-if="vendor === 'vendor_1'">
							<div class="w-full flex justify-between space-x-1">
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
								<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
								<button @click="openModel()" class="btn btn-primary p-1">
									<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
								</button>
							</div>
							<div class="bg-gray-200 !border !border-orange-200">
								<div class="page">
									<div class="subpage">
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
										<table class="table-bosrdered w-full !text-xs mb-0">
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
										<table class="table-bsordered w-full !text-xs mb-2">
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
										<table class="table-bordered w-full !text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="50%">Scope of Work</td>
												<td class="p-1" width="50%">Offer</td>
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
												<td class="align-top">
													<textarea name="" id="" class="resize w-full  h-screen !max-h-[300px] !min-h-[100] p-1"></textarea>
												</td>
											</tr>
										</table>
										<table class="table-bordered w-full !text-xs mb-2">
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
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="p-1 w-full h-10 !align-top"></textarea>
												</td>
												<td class="align-top p-0">
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="150">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="200">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center" placeholder="00.00" value="300">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
												</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Mouse</td>
												<td class="align-top">
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="p-1 w-full h-10 !align-top"></textarea>
												</td>
												<td class="align-top p-0">
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="150">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="200">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center" placeholder="00.00" value="300">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
												</td>
											</tr>
										</table>

										<table class="table-bordered w-full !text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="">Notes</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Governor Specifications: Make: Woodward Model: UG 40 Governor RPM: 487 - 1120 Designation Number: G8530 410 Serial Number: 1178418
												</td>
											</tr>
										</table>
										
										<br>
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div> -->
									</div>
								</div>
							</div>
						</div>
						<div v-else-if="vendor === 'vendor_2'">
							<div class="w-full flex justify-between space-x-1">
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
								<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
								<button @click="openModel()" class="btn btn-primary p-1">
									<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
								</button>
							</div>
							<div class="bg-gray-200 !border !border-orange-200">
								<div class="page">
									<div class="subpage">
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
										<table class="table-bosrdered w-full !text-xs mb-0">
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
										<table class="table-bsordered w-full !text-xs mb-2">
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
										<table class="table-bordered w-full !text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="50%">Scope of Work</td>
												<td class="p-1" width="50%">Offer</td>
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
												<td class="align-top">
													<textarea name="" id="" class="resize w-full  h-screen !max-h-[300px] !min-h-[100] p-1"></textarea>
												</td>
											</tr>
										</table>
										<table class="table-bordered w-full !text-xs mb-2">
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
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="p-1 w-full h-10 !align-top"></textarea>
												</td>
												<td class="align-top p-0">
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="150">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="200">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center" placeholder="00.00" value="300">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
												</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Mouse</td>
												<td class="align-top">
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="p-1 w-full h-10 !align-top"></textarea>
												</td>
												<td class="align-top p-0">
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="150">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="200">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center" placeholder="00.00" value="300">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
												</td>
											</tr>
										</table>

										<table class="table-bordered w-full !text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="">Notes</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Governor Specifications: Make: Woodward Model: UG 40 Governor RPM: 487 - 1120 Designation Number: G8530 410 Serial Number: 1178418
												</td>
											</tr>
										</table>
										
										<br>
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div> -->
									</div>
								</div>
							</div>
						</div>
						<div v-else>
							<div class="w-full flex justify-between space-x-1">
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-on:click="vendor = ''">Lectrix Solutions Electrical Supplies & Services Cebu</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_1'">MF Computer Solutions, Inc.</button>
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-700 !border-orange-100 !bg-orange-100" v-on:click="vendor = 'vendor_2'">Nexus Industrial Prime Solutions Corp.</button>
								<!-- <button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !text-orange-900 !border-orange-200 !bg-orange-200">Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.</button> -->
								<button @click="openModel()" class="btn btn-primary p-1">
									<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
								</button>
							</div>
							<div class="bg-gray-200 !border !border-orange-200">
								<div class="page">
									<div class="subpage">
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
										<table class="table-bosrdered w-full !text-xs mb-0">
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
										<table class="table-bsordered w-full !text-xs mb-2">
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
										<table class="table-bordered w-full !text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="50%">Scope of Work</td>
												<td class="p-1" width="50%">Offer</td>
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
												<td class="align-top">
													<textarea name="" id="" class="resize w-full  h-screen !max-h-[300px] !min-h-[100] p-1"></textarea>
												</td>
											</tr>
										</table>
										<table class="table-bordered w-full !text-xs mb-2">
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
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="p-1 w-full h-10 !align-top"></textarea>
												</td>
												<td class="align-top p-0">
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="150">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="200">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center" placeholder="00.00" value="300">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
												</td>
											</tr>
											<tr>
												<td class="p-1 align-top text-center">1</td>
												<td class="p-1 align-top text-center">5</td>
												<td class="p-1 align-top">Mouse</td>
												<td class="align-top">
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="border-b p-1 w-full h-10 !align-top"></textarea>
													<textarea class="p-1 w-full h-10 !align-top"></textarea>
												</td>
												<td class="align-top p-0">
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="150">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center text-xs" placeholder="00.00" value="200">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
													<div class="!h-10 border-b flex ">
														<input type="text" class="p-1 w-full !align-top text-center" placeholder="00.00" value="300">
														<select name="" id="" class=" p-1 !px-0 !align-top text-center">
															<option value="">PHP</option>
														</select>
													</div>
												</td>
											</tr>
										</table>

										<table class="table-bordered w-full !text-xs mb-2">
											<tr class="bg-gray-100">
												<td class="p-1" width="">Notes</td>
											</tr>
											<tr>
												<td class="p-1 align-top">
													Governor Specifications: Make: Woodward Model: UG 40 Governor RPM: 487 - 1120 Designation Number: G8530 410 Serial Number: 1178418
												</td>
											</tr>
										</table>
										
										<br>
										<!-- <div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div> -->
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row my-2"> 
							<div class="col-lg-3 col-md-3">
								<div class="">
									<button type="submit" class="btn btn-primary">Canvass Complete</button>
								</div>
							</div>
							<div class="col-lg-9 col-md-3">
								<ol class="flex items-center w-full">
									<li class="flex w-full items-center text-white after:w-full after:h-1 after:border-b after:border-green-500 after:border-4 after:inline-block-800">
										<span class="flex items-center font-bold justify-center w-10 h-10 bg-green-500 rounded-full lg:h-10 lg:w-10 shrink-0">
											V1
										</span>
									</li>
									
									<li class="flex w-full items-center text-gray-600 after:w-full after:h-1 after:border-b after:border-gray-200 after:border-4 after:inline-block-700">
										<span class="flex items-center font-bold justify-center w-10 h-10 bg-gray-200 rounded-full lg:h-10 lg:w-10 shrink-0">
											V2
										</span>
									</li>
									<li class="flex w-full items-center text-gray-600 after:w-full after:h-1 after:border-b after:border-gray-200 after:border-4 after:inline-block-700">
										<span class="flex items-center font-bold justify-center w-10 h-10 bg-gray-200 rounded-full lg:h-10 lg:w-10 shrink-0">
											V3
										</span>
									</li>
									<li class="!w-30">
										<!-- <a href="" class="btn !bg-gray-200 !w-36">Print TE</a> -->
										<a href="/pur_aoq/print_te" class="btn !bg-green-500 text-white  !w-36">Print TE</a>
									</li>
								</ol>
							</div>
						</div>
						<!-- <hr class="border-dashed">	
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-between space-x-2">
									<button type="submit" class="btn btn-primary mr-2 w-">Canvass Complete</button>
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
				<div class="modal__content w-8/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Vendor</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items">
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
								<table class="table-bordered w-full !text-xs mb-2">
									<tr class="bg-gray-100">
										<td class="p-1" width="50%">Scope of Work</td>
										<td class="p-1" width="50%">Offer</td>
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
										<td class="align-top">
											<textarea name="" id="" class="resize w-full  h-screen !max-h-[300px] !min-h-[100] p-1"></textarea>
										</td>
									</tr>
								</table>
								<table class="table-bordered w-full !text-xs">
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
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="p-1 align-top text-center">1</td>
										<td class="p-1 align-top text-center">5</td>
										<td class="p-1 align-top">Mouse</td>
										<td class="align-top">
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/job_quote/print" class="btn btn-primary mr-2 w-44">Save</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>