<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"

	const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const dangerAlert_item1 = ref(false)
	const dangerAlert_item2 = ref(false)
	const dangerAlert_item3 = ref(false)
	const dangerAlert_item4 = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
	const opendangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
	const opendangerAlert_item = () => {
		dangerAlert_item.value = !dangerAlert_item.value
	}
	const opendangerAlert_item1 = () => {
		dangerAlert_item1.value = !dangerAlert_item1.value
	}
	const opendangerAlert_item2 = () => {
		dangerAlert_item2.value = !dangerAlert_item2.value
	}
	const opendangerAlert_item3 = () => {
		dangerAlert_item3.value = !dangerAlert_item3.value
	}
	const opendangerAlert_item4 = () => {
		dangerAlert_item4.value = !dangerAlert_item4.value
	}
    const openDangerAlert = () => {
		modalEdit.value = !modalEdit.value
	}
	const closeAlert = () => {
		dangerAlert.value = !hideAlert.value
		dangerAlert_item.value = !hideAlert.value
		dangerAlert_item1.value = !hideAlert.value
		dangerAlert_item2.value = !hideAlert.value
		dangerAlert_item3.value = !hideAlert.value
		dangerAlert_item4.value = !hideAlert.value
	}

	const removeItem1 = () => {
		const item1 = document.getElementById("item1");
		dangerAlert_item.value = !hideAlert.value
		item1.remove();
	}

	const removeItem2 = () => {
		const item2 = document.getElementById("item2");
		dangerAlert_item1.value = !hideAlert.value
		item2.remove();
	}

	const removeItem3 = () => {
		const item3 = document.getElementById("item3");
		dangerAlert_item2.value = !hideAlert.value
		item3.remove();
	}

	const removeScopeitem1 = () => {
		const scope = document.getElementById("scope");
		dangerAlert_item3.value = !hideAlert.value
		scope.remove();
	}

	const removeNotes1 = () => {
		const notes = document.getElementById("notes");
		dangerAlert_item4.value = !hideAlert.value
		notes.remove();
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Job Order Request <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_req">Job Order Request</a></li>
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
					<div class="pt-1">
						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Job Order Request: </span>
								<span class="text-sm text-gray-700">Bacolod</span>
							</div>
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
								<span class="text-sm text-gray-700">01/16/24</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">JO Number: </span>
								<span class="text-sm text-gray-700">JO-BCD24-1209</span>
							</div>
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">New JO Number: </span>
								<span class="text-sm text-gray-700">JO-CENPRI24-1002</span>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
								<span class="text-sm text-gray-700">IT Department</span>
							</div>
							<div class="col-lg-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
								<span class="text-sm text-gray-700">0912</span>
							</div>
							<div class="col-lg-2">
								<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
								<span class="text-sm text-gray-700">X</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
								<span class="text-sm text-gray-700">IT Department</span>
							</div>
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
								<span class="text-sm text-gray-700">Replace damage monitor, mouse and keyboard</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Project/Activity: </span>
								<span class="text-sm text-gray-700">Sample Project</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="2%">#</td>
										<td class="p-1 uppercase" width="">Scope Of Works</td>
										<td class="p-1 uppercase text-center" width="10%">Qty</td>
										<td class="p-1 uppercase text-center" width="10%">UOM</td>
										<td class="p-1 uppercase text-center" width="10%">Unit Cost</td>
										<td class="p-1 uppercase text-center" width="10%">Total Cost</td>
										<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
											<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
										</td>
									</tr>
									<tr id="scope">
										<td class="p-1 text-center align-top">1</td>
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
										<td class="p-1 align-top text-center">23</td>
										<td class="p-1 align-top text-center">lot</td>
										<td class="p-1 align-top text-center">54</td>
										<td class="p-1 align-top text-center">545</td>
										<td class="text-center">
											<button class="btn btn-danger p-1"  @click="opendangerAlert_item3()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="2%">#</td>
										<td class="p-1 uppercase text-center" width="7%">Qty</td>
										<td class="p-1 uppercase text-center" width="7%">UOM</td>
										<td class="p-1 uppercase" width="20%">PN No.</td>
										<td class="p-1 uppercase" width="">Item Description</td>
										<td class="p-1 uppercase" width="10%">WH Stocks</td>
										<td class="p-1 uppercase" width="15%">Date Needed</td>
										<td class="p-1 uppercase" width="1%" align="center">
											<span>
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 " ></XMarkIcon>
											</span>
										</td>
									</tr>
									<tr id="item1">
										<td class="p-1 text-center">1</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-001</td>
										<td class="p-1">Monitor</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="text-center">
											<button class="btn btn-xs btn-danger p-1" @click="opendangerAlert_item()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr id="item2">
										<td class="p-1 text-center">1</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-222</td>
										<td class="p-1">Mouse</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="text-center">
											<button class="btn btn-xs btn-danger p-1" @click="opendangerAlert_item1()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr id="item3">
										<td class="p-1 text-center">1</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-333</td>
										<td class="p-1">Keyboard</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="text-center">
											<button class="btn btn-xs btn-danger p-1" @click="opendangerAlert_item2()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="1%">#</td>
										<td class="p-1 uppercase" width="">Notes</td>
										<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
											<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
										</td>
									</tr>
									<tr id="notes">
										<td class="p-1 text-center align-top">1</td>
										<td class="p-1 align-top">
											Governor Specifications: Make: Woodward Model: UG 40 Governor RPM: 487 - 1120 Designation Number: G8530 410 Serial Number: 1178418
										</td>
										<td class="text-center">
											<button class="btn btn-danger p-1"  @click="opendangerAlert_item4()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<br>
						<!-- <hr class="border-dashed"> -->
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-danger mr-2 w-36" @click="opendangerAlert()">Cancel</button>
									<button type="submit" class="btn btn-primary mr-2 w-36">Print</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert }">
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
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeAlert()">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item }">
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this Item?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeItem1(index)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item1 }">
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this Item?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeItem2(index)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item2 }">
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this Item?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeItem3(index)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item3 }">
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this scope of work?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeScopeitem1(index)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item4 }">
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this note?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeNotes1(index)">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>