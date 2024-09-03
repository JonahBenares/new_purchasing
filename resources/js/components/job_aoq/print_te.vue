<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"

	const showModal = ref(false)
	const hideModal = ref(true)
	const openModel = () => {
		showModal.value = !showModal.value
	}
	const closeModal = () => {
		showModal.value = !hideModal.value
	}

	const pr_det = ref(false)
</script>
<template>
	<navigation>
		<div class="bg-yellow-400 text-white px-3 py-2 font-bold">For Technical Evaluation</div>
		<div class="bg-white p-4 ">
			<div class="overflow-x-scroll">
				<div class="">
					<table class="w-full !text-xs mb-3 ">
						<tr>
							<td class="font-bold pr-1" width="8%">JOR No: </td>
							<td class="">JOR-CENPRI24-1002</td>
							<td class=" font-bold pr-1" width="8%">AOQ No: </td>
							<td class="">AOQ-1009-1001</td>
							<td class=" font-bold pr-1" width="8%">Requested By: </td>
							<td class="">Henne Tanan</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Department: </td>
							<td class="">IT Department</td>
							<td class=" font-bold pr-1">Date: </td>
							<td class="">05/16/24</td>
							<td class=" font-bold pr-1">Date Needed: </td>
							<td class="">01/16/24</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">End-Use:</td>
							<td class="">IT Department</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Purpose:</td>
							<td class="">Replace damage monitor, mouse and keyboard</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Project Title:</td>
							<td class="">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</td>
						</tr>
					</table>
					<table class="table-bordered !text-xs mb-3" width="250%">
						<tr>
							<td class="bg-gray-50 " colspan="4"></td>
							<!-- loop vendors here start -->
							<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
								<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
								<!-- <p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
								<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
								<div class="flex justify-center space-x-2">
									<span>Mary Marie</span>
									<span>|</span>
									<span>(034) 9872-2772</span>
								</div>
							</td>
							<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
								<p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
								<!-- <p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
								<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
								<div class="flex justify-center space-x-2">
									<span>Mary Marie</span>
									<span>|</span>
									<span>(034) 9872-2772</span>
								</div>
							</td>
							<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
								<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
								<!--<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p>
								<p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>-->
								<div class="flex justify-center space-x-2"> 
									<span>Mary Marie</span>
									<span>|</span>
									<span>(034) 9872-2772</span>
								</div>
							</td>
							<!-- loop vendors here end-->
						</tr>
						
						<tr>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="1%">#</td>
							<td class="uppercase bg-gray-100 p-1 align-top" width="10%">Description</td>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="2%">Qty</td>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="2%">UOM</td>
							<!-- loop offer header per vendor here start -->
							<td class="uppercase bg-gray-100 p-1 " width="10%">Offer</td>
							<td class="uppercase bg-gray-100 p-1 text-center"  width="3%">Unit Price</td>
							<td class="uppercase bg-gray-100 p-1 text-center" colspan="2" width="3%">Amount</td>
							<td class="uppercase bg-gray-100 p-1 text-center" width="5%">Comment</td>
							<!-- loop offer header per vendor here end-->
								<!-- loop offer header per vendor here start -->
							<td class="uppercase bg-gray-100 p-1 " width="10%">Offer</td>
							<td class="uppercase bg-gray-100 p-1 text-center"  width="3%">Unit Price</td>
							<td class="uppercase bg-gray-100 p-1 text-center" colspan="2" width="3%">Amount</td>
							<td class="uppercase bg-gray-100 p-1 text-center" width="5%">Comment</td>
							<!-- loop offer header per vendor here end-->
								<!-- loop offer header per vendor here start -->
							<td class="uppercase bg-gray-100 p-1 " width="10%">Offer</td>
							<td class="uppercase bg-gray-100 p-1 text-center"  width="3%">Unit Price</td>
							<td class="uppercase bg-gray-100 p-1 text-center" colspan="2" width="3%">Amount</td>
							<td class="uppercase bg-gray-100 p-1 text-center" width="5%">Comment</td>
							<!-- loop offer header per vendor here end-->
						</tr>
						<tr>
							<td class="bg-gray-50 p-1 uppercase" colspan="19">
								Supply of technical expertise for the following:
							</td>
							<!-- loop vendors here end-->
						</tr>
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
							<tr>
								<td class="p-1 align-top text-center" rowspan="4">1</td>
								<td class="p-1 align-top" rowspan="4">
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
								<td class="p-1 align-top text-center" rowspan="4">5</td>
								<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
							</tr>
							<!-- loop here if 3 and below offers here -->
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
							<!-- loop here if 3 and below offers here -->
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
						<tr>
							<td class="bg-gray-50 p-1 uppercase" colspan="19">
								Materials
							</td>
						</tr>
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
							<tr>
								<td class="p-1 align-top text-center" rowspan="4">1</td>
								<td class="p-1 align-top" rowspan="4">Mouse</td>
								<td class="p-1 align-top text-center" rowspan="4">5</td>
								<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
							</tr>
							<!-- loop here if 3 and below offers here -->
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>

										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>150.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>750.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
							<!-- loop here if 3 and below offers here -->
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
							<tr>
								<td class="p-1 align-top text-center" rowspan="4">2</td>
								<td class="p-1 align-top" rowspan="4">Keyboard</td>
								<td class="p-1 align-top text-center" rowspan="4">5</td>
								<td class="p-1 align-top text-center" rowspan="4">pc/s</td>
							</tr>
							<!-- loop here if 3 and below offers here -->
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
										<td class="p-1">
											sample 1
										</td>
										<td class="p-1 align-top bg-yellow-300">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>100.00</span>
											</div>
										</td>
										<td class="p-1 align-top" colspan="2">
											<div class="flex justify-between space-x-1">
												<span>Php</span>
												<span>500.00</span>
											</div>
										</td>
										
										<td class="p-1 align-top"></td>
									<!-- loop offers per vendor here -->
								</tr>
							<!-- loop here if 3 and below offers here -->
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
						<tr class="!border-0">
							<td class="!border-0" colspan="4"><br></td>
							<td class="!border-0" colspan="4"><br></td>
						</tr>
						<tr class="!border-0">
						<td class="!border-0 text-center">a.</td>
							<td class="!border-0 text-center" colspan="1">Price Validity</td>
							<td class="!border-0" colspan="2"></td>	
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan="4"></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan="4"></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan="4"></td>
						</tr>
						<tr class="!border-0">
							<td class="!border-0 text-center">b.</td>
							<td class="!border-0 text-center" colspan="1">Payment Terms</td>
							<td class="!border-0" colspan="2"></td>	
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan=""></td>
							<td class="!border-0 text-right px-2" colspan="">Legend:</td>
							<td class="!border-0" colspan="2"></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan=""></td>
							<td class="!border-0 text-right px-2" colspan="">Legend:</td>
							<td class="!border-0" colspan="2"></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan=""></td>
							<td class="!border-0 text-right px-2" colspan="">Legend:</td>
							<td class="!border-0" colspan="2"></td>
						</tr>
						<tr class="!border-0">
							<td class="!border-0 text-center">c.</td>
							<td class="!border-0 text-center" colspan="1">Work Duration</td>
							<td class="!border-0" colspan="2"></td>	
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
							<td class="!border-0 bg-lime-500" width="1%"></td>
							<td class="!border-0" colspan=""></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
							<td class="!border-0 bg-lime-500" width="1%"></td>
							<td class="!border-0" colspan=""></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
							<td class="!border-0 bg-lime-500" width="1%"></td>
						</tr>
						<tr class="!border-0">
							<td class="!border-0 text-center">d.</td>
							<td class="!border-0 text-center" colspan="1">Item's Warranty</td>
							<td class="!border-0" colspan="2"></td>	
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
							<td class="!border-0 bg-yellow-300" colspan="1"></td>
							<td class="!border-0" colspan=""></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
							<td class="!border-0 bg-yellow-300" colspan="1"></td>
							<td class="!border-0" colspan=""></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
							<td class="!border-0 bg-yellow-300" colspan="1"></td>
						</tr>
						<tr class="!border-0">
							<td class="!border-0 text-center">e.</td>
							<td class="!border-0 text-center" colspan="1">In-land Freight</td>
							<td class="!border-0" colspan="2"></td>	
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan="4"></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan="4"></td>
							<td class="!border-0 !border-b" colspan="1"></td>
							<td class="!border-0" colspan="4"></td>
						</tr>
						<tr class="!border-0">
							<td class="!border-0" colspan="4"><br></td>
							<td class="!border-0" colspan="4"><br></td>
						</tr>
						<tr class="!border-0">
							<td class="!border-0" colspan="4"><br></td>
							<td class="!border-0" colspan="4"><br></td>
						</tr>
					</table>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-lg-12">
					<div class="flex justify-center">
						<a href="/pur_aoq/print_te" class="btn btn-primary mr-2 w-44">Print TE</a>
					</div>
				</div>
			</div>
		</div>
	</navigation>
</template>