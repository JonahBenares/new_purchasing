<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"

	const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
	const opendangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
	const opendangerAlert_item = () => {
		dangerAlert_item.value = !dangerAlert_item.value
	}
    const openDangerAlert = () => {
		modalEdit.value = !modalEdit.value
	}
	const closeAlert = () => {
		dangerAlert.value = !hideAlert.value
		dangerAlert_item.value = !hideAlert.value
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
				<div class="card">
				<div class="card-body">
					<hr class="border-dashed mt-0">
					<div class="pt-1" id="printable">
						<div class="row">
							<div class="col-lg-6 col-sm-6 col-md-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Purchase Request: </span>
								<span class="text-sm text-gray-700">Bacolod</span>
							</div>
							<div class="col-lg-6 col-sm-6 col-md-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
								<span class="text-sm text-gray-700">01/16/24</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-sm-6 col-md-6">
								<span class="text-sm text-gray-700 font-bold pr-1">PR Number: </span>
								<span class="text-sm text-gray-700">PR-BCD24-1209</span>
							</div>
							<div class="col-lg-6 col-sm-6 col-md-6">
								<span class="text-sm text-gray-700 font-bold pr-1">New PR Number: </span>
								<span class="text-sm text-gray-700">PR-CENPRI24-1002</span>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6 col-sm-6 col-md-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
								<span class="text-sm text-gray-700">IT Department</span>
							</div>
							<div class="col-lg-4 col-sm-4 col-md-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
								<span class="text-sm text-gray-700">0912</span>
							</div>
							<div class="col-lg-2 col-sm-2 col-md-2">
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
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="5%">#</td>
										<td class="p-1 uppercase text-center" width="7%">Qty</td>
										<td class="p-1 uppercase text-center" width="7%">UOM</td>
										<td class="p-1 uppercase" width="20%">PN No.</td>
										<td class="p-1 uppercase" width="">Item Description</td>
										<td class="p-1 uppercase" width="10%">WH Stocks</td>
										<td class="p-1 uppercase" width="15%">Date Needed</td>
										<td class="p-1 uppercase" width="15%">Recom Date</td>
										<td class="p-1 uppercase po_buttons" width="1%" align="center">
											<span>
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 " ></XMarkIcon>
											</span>
										</td>
									</tr>
									<tr>
										<td class="p-1 text-center">1</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-001</td>
										<td class="p-1">Monitor</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="p-1"><input type="date" class="w-full"></td>
										<td class="text-center po_buttons">
											<button class="btn btn-xs btn-danger p-1" @click="opendangerAlert_item()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr>
										<td class="p-1 text-center">1</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-222</td>
										<td class="p-1">Mouse</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="p-1"><input type="date" class="w-full"></td>
										<td class="text-center po_buttons">
											<button class="btn btn-xs btn-danger p-1" @click="opendangerAlert_item()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr>
										<td class="p-1 text-center">1</td>
										<td class="p-1 text-center">5</td>
										<td class="p-1 text-center">pc/s</td>
										<td class="p-1">PN-0991-333</td>
										<td class="p-1">Keyboard</td>
										<td class="p-1"></td>
										<td class="p-1">08/25/24</td>
										<td class="p-1"><input type="date" class="w-full"></td>
										<td class="text-center po_buttons">
											<button class="btn btn-xs btn-danger p-1" @click="opendangerAlert_item()">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<br>
						<!-- <hr class="border-dashed"> -->
						<div class="row my-2 po_buttons" > 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-danger mr-2 w-36" @click="opendangerAlert()">Cancel</button>
									<button type="submit" class="btn btn-primary mr-2 w-36" @click="printDiv()">Print</button>
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
									<h5 class="leading-tight">Are you sure you want to remove this item?</h5>
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
	</navigation>
</template>