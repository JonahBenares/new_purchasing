<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const printDiv = () => {
		window.print();
	}
</script>
<template>
	<navigation>
		<div class="row" id="breadcrumbs" >
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Delivery Receipt <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_dr">JO Delivery Receipt</a></li>
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
					<hr class="border-dashed mt-0">
					<div class="pt-1" id="printable">
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-8">
								<span class="text-sm text-gray-700 font-bold pr-1">JOI No: </span>
								<span class="text-sm text-gray-700">PO-CENPRI24-1001</span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<span class="text-sm text-gray-700 font-bold pr-1">DR No: </span>
								<span class="text-sm text-gray-700">DR-CENPRI24-1001</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-8">
								<span class="text-sm text-gray-700 font-bold pr-1">JOR No: </span>
								<span class="text-sm text-gray-700">JOR-CENPRI24-1002</span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
								<span class="text-sm text-gray-700">05/16/24</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-8">
								<span class="text-sm text-gray-700 font-bold pr-1">End-use: </span>
								<span class="text-sm text-gray-700">IT Department</span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Requestor: </span>
								<span class="text-sm text-gray-700">Henne Marie Tanan</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Purpose:</span>
								<span class="text-sm text-gray-700">Replace damage monitor, mouse and keyboard</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Project Title:</span>
								<span class="text-sm text-gray-700">Replace damage monitor, mouse and keyboard</span>
							</div>
						</div>						
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered text-xs mt-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="2%">#</td>
										<td class="p-1 uppercase text-center" width="25%">Supplier</td>
										<td class="p-1 uppercase text-center" width="25%">Description</td>
										<td class="p-1 uppercase text-center" width="7%">To Deliver</td>
										<td class="p-1 uppercase text-center" width="8%">DLVRD Qty</td>
										<td class="p-1 uppercase text-center" width="5%">Received</td>
										<td class="p-1 uppercase" width="5%">UOM</td>
										<td class="p-1 uppercase" width="5%">Remarks</td>
									</tr>
									<tr>
										<td class="p-1 text-center">1</td>
										<td class="p-1 ">MF Computer Solutions, Inc.</td>
										<td class="p-1 ">
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
										</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center"></td>
										<td class="p-1"></td>
										<td class="p-1"></td>
										<td class="p-1">remarks</td>
									</tr>
									<tr>
										<td class="p-1 text-center">2</td>
										<td class="p-1 ">MF Computer Solutions, Inc.</td>
										<td class="p-1 ">Mouse</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center"></td>
										<td class="p-1"></td>
										<td class="p-1"></td>
										<td class="p-1">remarks</td>
									</tr>
									<tr>
										<td class="p-1 text-center">3</td>
										<td class="p-1 ">MF Computer Solutions, Inc.</td>
										<td class="p-1 ">Keyboard</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center"></td>
										<td class="p-1"></td>
										<td class="p-1"></td>
										<td class="p-1">remarks</td>
									</tr>
								</table>
							</div>
						</div>
						<br>
						<div class="row mt-4 mb-4">
							<div class="col-lg-12">
								<table class="w-full text-xs">
									<tr>
										<td width="10%"></td>
										<td class="text-center" width="20%">Prepared by</td>
										<td width="10%"></td>
										<td class="text-center" width="20%">Driver</td>
										<td width="10%"></td>	
									</tr>
									<tr>
										<td></td>
										<td class="text-center border-b"><br><br></td>
										<td></td>
										<td class="text-center border-b"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td class="text-center p-1">Henne Tanant</td>
										<td></td>
										<td class="text-center p-1">Glenn Paul Marie</td>
										<td></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4 mb-4">
							<div class="col-lg-12">
								<table class="w-full text-xs">
									<tr>
										<td class="text-center" width="20%">Received by</td>
										<td width="2%"></td>
										<td class="text-center" width="20%">Complete & Accepted by Enduser</td>
										<td width="2%"></td>
										<td class="text-center" width="20%">Witnessed by</td>
									</tr>
									<tr>
										<td class="text-center border-b"><br><br></td>
										<td></td>
										<td class="text-center border-b"></td>
										<td></td>
										<td class="text-center border-b"></td>
									</tr>
									<tr>
										<td class="text-center p-1">Print Name & Signature with Date Received</td>
										<td></td>
										<td class="text-center p-1">Print Name & Signature with Date Received</td>
										<td></td>
										<td class="text-center p-1">Print Name & Signature with Date Received</td>
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
						<hr class="border-dashed">
						<div class="row my-2 po_buttons" > 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-primary mr-2 w-36" @click="printDiv()">Print</button>
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