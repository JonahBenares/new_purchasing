<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon, ExclamationTriangleIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const buttons_set =  ref()
	const approval_set =  ref(false)

	const dangerAlert = ref(false)
	const successAlert = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
    const approveAlert = ref(false)
	const hide_button = ref()
	const hideAlert = ref(true)
	const openDangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
    const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}
	const openInfoAlert = () => {
		infoAlert.value = !infoAlert.value
	}
	const openApproveAlert = () => {
		approveAlert.value = !approveAlert.value
	}
	const openWarningAlert = () => {
		warningAlert.value = !warningAlert.value
	}
	const closeAlert = () => {
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		warningAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
		approveAlert.value = !hideAlert.value
	}

	const closeInfoAlert = () => {
		infoAlert.value = !hideAlert.value
	}
	const closeInfoAlert2 = () => {
		infoAlert.value = !hideAlert.value
		approval_set.value = !approval_set.value
		buttons_set.value = !hide_button.value
	}

	const showAddVendor = ref(false)
	const showPreview = ref(false)
	const hideModal = ref(true)
	const openAddVendor = () => {
		showAddVendor.value = !showAddVendor.value
	}
	const openPreview = () => {
		showPreview.value = !showPreview.value
	}
	const closeModal = () => {
		showAddVendor.value = !hideModal.value
		showPreview.value = !hideModal.value
	}
	const save_button = ref();

	let terms_list=ref([]);
	let terms_text=ref("");
	let other_list=ref([]);
	let other_text=ref("");
	const addRowTerms= () => {
		if(terms_text.value!=''){
			const terms = {
				terms_condition:terms_text.value,
			}
			terms_list.value.push(terms)
			terms_text.value='';
			document.getElementById('check_terms').placeholder=""
			document.getElementById('check_terms').style.backgroundColor = '#FFFFFF';
		}else{
			document.getElementById('check_terms').placeholder="Please fill in Terms and Condition."
			document.getElementById('check_terms').style.backgroundColor = '#FAA0A0';
		}
	}
	const removeTerms = (index) => {
		terms_list.value.splice(index,1)
	}

	const addRowOther= () => {
		if(other_text.value!=''){
			const others = {
				other_ins:other_text.value,
			}
			other_list.value.push(others)
			other_text.value='';
			document.getElementById('check_others').placeholder=""
			document.getElementById('check_others').style.backgroundColor = '#FFFFFF';
		}else{
			document.getElementById('check_others').placeholder="Please fill in Other instructions."
			document.getElementById('check_others').style.backgroundColor = '#FAA0A0';
		}
	}
	const removeOthers = (index) => {
		other_list.value.splice(index,1)
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Issuance <small>Revise</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_issue">JO Issuance</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Revise</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<div class="pt-1" id="printable">
						<span class="font-bold uppercase text-lg text-center text-yellow-500">CHANGE ORDER FORM</span>
						<hr class="border-dashed mt-">
						<div>
							<div class="row">
								<div class="col-lg-1 col-sm-1 col-md-1">
									<span class="text-sm">TO:</span>
								</div>
								<div class="col-lg-11 col-sm-11 col-md-11">
									<p class="m-0 font-bold capitalize">MF Computer Solutions, Inc.</p>
									<p class="m-0">Beverly Marie Dy</p>
									<p class="m-0">Taculing Road, Bacolod City 6100</p>
									<p class="m-0">(034) 434 9823 / 704-2063</p>
								</div>
							</div>
							<hr class="border-dashed">
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Needed: </span>
										<input type="text" class="border-b bg-white w-full" disabled>
									</div>
								</div>
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-52">Completion of Work: </span>
										<input type="text" class="border-b bg-white w-full" disabled>
									</div>	
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Prepared: </span>
										<input type="text" class="border-b bg-white w-full" disabled>
									</div>
								</div>
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-52">CENPRI JOR No: </span>
										<input type="text" class="border-b bg-white w-full" disabled>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Start of Work: </span>
										<input type="text" class="border-b bg-white w-full" disabled>
									</div>
								</div>
								<div class="col-lg-6 col-sm-6 col-md-6">
									<div class="flex">
										<span class="text-sm text-gray-700 font-bold pr-1 !w-52">JO No: </span>
										<input type="text" class="border-b bg-white w-full" disabled>
									</div>
								</div>
							</div>
							
							<div class="" >
								<br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="font-bold uppercase text-sm text-gray-500">Current Data</span>
                                        <div class="border-2">
											<table class="table-bordered w-full !text-xs">
												<tr class="!border-b-3">
													<td colspan="7" class="py-2">
														<p class="text-sm font-bold text-gray-600 text-center m-0">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</p>
														<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
													</td>
												</tr>
												<tr class="bg-gray-100">
													<td class="uppercase p-1" colspan="3">Scope of Work</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="10%">Total</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1" colspan="3">
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
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">pc</td>
													<td class="border-y-none p-1 text-right">100.00</td>
													<td class="border-y-none p-1 text-right">500.00</td>
												</tr>
												<tr class="bg-gray-100">
													<td class="p-1 text-center" width="3%">#</td>
													<td class="p-1" colspan="2">Materials:</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="10%">Total</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1 text-center">1</td>
													<td class="border-y-none p-1" colspan="2">Monitor</td>
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">lot</td>
													<td class="border-y-none p-1 text-right">100.00</td>
													<td class="border-y-none p-1 text-right">500.00</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1 text-center">2</td>
													<td class="border-y-none p-1" colspan="2">Mouse</td>
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">pc</td>
													<td class="border-y-none p-1 text-right">100.00</td>
													<td class="border-y-none p-1 text-right">500.00</td>
												</tr>
												<tr class="">
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
												</tr>
												<tr class="">
													<td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="5">
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">JOR Number:</span>PR-19772-8727</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
													</td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Total Labor</td>
													<td class="p-0"><input disabled type="text" class="w-full bg-white p-0.5 text-right pr-1" value="200.00"></td>
												</tr>
												
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
													<td class="p-0">
														<div class="flex">
															<input disabled type="text" class="w-10 bg-white border-r text-center" placeholder="%" value="">
															<input disabled type="text" class="w-full bg-white p-1 text-right" value="">
														</div>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Labor</td>
													<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" value="200.00"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Material</td>
													<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" value="100.00"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">1000.00</td>
												</tr>
											</table>
										</div>
                                    </div>
                                </div>
                                <br>
								<div class="row">
									<div class="col-lg-12">
                                        <span class="font-bold uppercase text-sm text-yellow-500">New Data</span>
										<div class="border-2 border-yellow-400">
											<table class="table-bordered w-full !text-xs">
												<tr class="!border-b-3 bg-yellow-50">
													<td colspan="7" class="py-2">
														<textarea class="text-sm font-bold text-gray-600 bg-yellow-50  text-center m-0 w-full resize" rows="1">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</textarea>
														<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
													</td>
												</tr>
												<tr class="bg-yellow-100">
													<td class="uppercase p-1" colspan="3">Scope of Work</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="10%">Total</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1" colspan="3">
														<textarea class="font-bold w-full resize" rows="1">Supply of manpower/labor, laboratory tools/equipment, and technical expertise for the following:</textarea>
														<textarea name="" id="" class="w-full resize" rows="10">1. 1. Standard governor overhauling/dismantling, cleaning and replacement of parts as seen necessary (i.e. gaskets, bearings, o-rings, etc.)2. Inspection and checking of all parts for wear, cracks, corrosion and other damages.3. Repair and replacement of parts as seen upon inspection.4. Setting of internal parts and mounting of the governor.5. Calibration and bench testing for:5.1. Speed Setting and Indicator5.2. Speed Droop Setting and Indicator5.3. Load Limit Setting and Indicator6. Functional test of shut-down solenoid valve7. Testing and Commissioning8. Submission of inspection, service, commissioning and bench testing reports.9. Other works necessary for job completion.
														</textarea>
													</td>
													<td class="border-y-none p-1 text-center"><input type="text" value="5" class="w-full text-center"></td>
													<td class="border-y-none p-1 text-center"><input type="text" value="lot" class="w-full text-center"></td>
													<td class="border-y-none p-1 text-right"><input type="text" value="100.00" class="w-full text-center"></td>
													<td class="border-y-none p-1 text-right"><input type="text" value="500.00" class="w-full text-center"></td>
												</tr>
												<tr class="bg-yellow-100">
													<td class="p-1 text-center" width="3%">#</td>
													<td class="p-1" colspan="2">Materials:</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="10%">Total</td>
												</tr>
												<tr class="">
													<td class="p-1 text-center align-top">1</td>
													<td class="p-0 align-top " colspan="2">
														<textarea name="" id="" class="w-full  p-1 resize" rows="1">Monitor</textarea>
													</td>
													<td class="align-top text-center"><input type="text" class="w-full text-center p-1" value="5"></td>
													<td class="align-top text-center"><input type="text" class="w-full text-center p-1" value="lot"></td>
													<td class="align-top text-right"><input type="text" class="w-full text-center p-1" value="100.00"></td>
													<td class="align-top text-right"><input type="text" class="w-full text-center p-1" value="500.00"></td>
												</tr>
												<tr class="">
													<td class="p-1 text-center align-top">1</td>
													<td class="p-0 align-top " colspan="2">
														<textarea name="" id="" class="w-full  p-1 resize" rows="1">Mouse</textarea>
													</td>
													<td class="align-top text-center"><input type="text" class="w-full text-center p-1" value="5"></td>
													<td class="align-top text-center"><input type="text" class="w-full text-center p-1" value="lot"></td>
													<td class="align-top text-right"><input type="text" class="w-full text-center p-1" value="100.00"></td>
													<td class="align-top text-right"><input type="text" class="w-full text-center p-1" value="500.00"></td>
												</tr>
												<tr class="">
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
													<td class=""></td>
												</tr>
												<tr class="">
													<td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="5">
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">JOR Number:</span>PR-19772-8727</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
														<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
													</td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Total Labor</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-0.5 text-right pr-1" value="200.00"></td>
												</tr>
												
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
													<td class="p-0">
														<div class="flex">
															<input type="text" class="w-10 bg-yellow-50 border-r text-center" placeholder="%" value="">
															<input type="text" class="w-full bg-yellow-50 p-1 text-right" value="">
														</div>
													</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Labor</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right" value="200.00"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Material</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right" value="100.00"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">1000.00</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								
								<div class="row mt-2">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<table class="table-bordered !text-xs w-full">
											<tr>
												<td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
											</tr>
											<tr>
												<td class="p-0" colspan="2">
													<input type="text" class="p-1 w-full bg-yellow-50" v-model="terms_text" id="check_terms">
												</td>
												<td class="p-0" width="1">
													<button type="button" @click="addRowTerms" class="btn btn-primary p-1">
														<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
													</button>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">1.</td>
												<td class="align-top px-1" colspan="2">PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">2.</td>
												<td class="align-top px-1" colspan="2">Sub-standard items shall be returned to supplier @ no cost to CENPRI.</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">3.</td>
												<td class="align-top pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-14">Price is </span>
														<select name="" class="w-full bg-yellow-50" id="">
															<option value="">Inclusive of VAT</option>
															<option value="">Exclusive of VAT</option>
														</select>
													</div>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">4.</td>
												<td class="align-top  pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-32">Item Warranty </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr>
												<td class="align-top text-center" width="4%">5.</td>
												<td class="align-top  pl-1" colspan="2">
													<div class="flex justify-between">
														<span class="w-32">Delivery Term </span>
														<input name="" class="w-full bg-yellow-50 px-1" id="">
													</div>
												</td>
											</tr>
											<tr v-for="(t,index) in terms_list">
												<td class="align-top text-center" width="4%">{{ index + 6 }}.</td>
												<td class="px-1" colspan="2">
													<span class="w-32">{{ t.terms_condition }}</span>
												</td>
												<td class="p-0 align-top" width="1">
													<button type="button" class="btn btn-danger p-1">
														<XMarkIcon fill="none" @click="removeTerms(index)" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
										</table>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<table class="table-bordered !text-xs w-full">
											<tr>
												<td class="p-1 uppercase" colspan="3">Other Instructions</td>
											</tr>
											<tr>
												<td class="p-0" colspan="2">
													<input type="text" class="p-1 w-full bg-yellow-50" v-model="other_text" id="check_others">
												</td>
												<td class="p-0" width="1">
													<button type="button" @click="addRowOther" class="btn btn-primary p-1" >
														<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
													</button>
												</td>
											</tr>
											<tr v-for="(o, indexes) in other_list">
												<td class="px-1" colspan="2">{{ o.other_ins }}</td>
												<td class="p-0 align-top" width="1">
													<button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
											<tr>
												<td colspan="2" class="p-1">Sample Notes</td>
												<td class="p-0 align-top" width="1">
													<button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1">
														<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
													</button>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-lg-12">
										<table class="w-full ">
											<tr>
												<td></td>
												<td width="12%" class="font-bold text-sm text-gray-500"> Total Project Cost:</td>
												<td  width="20%" class="border-b border-gray-400 px-4 font-bold text-base text-gray-500"> 
													<div class="flex justify-between  text-lg">
														<span>PHP</span>
														<span>18,999.99</span>
													</div>
												</td>
												<td width="14%"></td>
												<td width="8%" class="font-bold text-sm text-gray-500">Conforme:</td>
												<td  width="30%" class="border-b border-gray-400 px-4"><input type="text" class="w-full text-center text-sm capitalize"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td class="text-xs text-center">Contractor's Signature Over Printed Name</td>
												<td></td>
											</tr>
										</table>
									</div>
								</div>
								<br>
								<div class="row mt-4 mb-4">
									<div class="col-lg-12">
										<table class="w-full text-xs">
											<tr>
												<td class="text-center" width="20%">Prepared by</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Reviewed/Checked by</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Recommended by</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Approved by</td>
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
												<td class="text-center p-1"><input type="text" class="text-center" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-1"><input type="text" class="text-center" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-1"><input type="text" class="text-center" placeholder="Employee Name"></td>
												<td></td>
												<td class="text-center p-1"><input type="text" class="text-center" placeholder="Employee Name"></td>
											</tr>
											<tr>
												<td class="text-center"><br><br></td>
												<td></td>
												<td class="text-center"></td>
												<td></td>
												<td class="text-center"></td>
											</tr>
											<tr>
												<td class="text-right" colspan="2">Work Completion Verified by: </td>
												<td class="text-center border-b" colspan="3"></td>
												<td class="text-center"></td>
											</tr>
											<tr>
												<td class="text-right" colspan="2"></td>
												<td class="text-center p-1" colspan="3">Signature over Printed Name</td>
												<td class="text-center"></td>
											</tr>
										</table>
									</div>
								</div>
								<hr	class="border-dashed mt-4">
								<div class="po_buttons">
									<div class="!hidden " :class="{ show:approval_set }">
										<div class="row">
											<div class="col-lg-12">
												<div class="flex justify-center">
													<button  class="btn btn-primary w-36" @click="printDiv()">Print</button>
												</div>
											</div>
										</div>
										<div class="row my-2 bg-yellow-50 px-2 py-3"> 
											<div class="col-lg-2 col-md-3 pl-0">
												<span class="text-sm p-1">Approve Date</span>
												<input type="date" class="form-control">
											</div>
											<div class="col-lg-3 col-md-3">
												<span class="text-sm p-1">Approve By</span>
												<select class="form-control">
													<option value="">Beverly Espareal</option>
												</select>
											</div>
											<div class="col-lg-6 col-md-6">
												<span class="text-sm p-1">Reason</span>
												<textarea name="" class="form-control" rows="1"></textarea>
											</div>
											<div class="col-lg-1 col-md-1">
												<span class="text-sm p-1"><br></span>
												<button @click="openApproveAlert()" class="btn btn-primary btn-sm" >Approve</button>
											</div>
										</div>
									</div>
									<div class="" :class="{ hidden:buttons_set }">
										<div class="row my-2" > 
											<div class="col-lg-12 col-md-12">
												<div class="flex justify-center space-x-2">
													<div class="flex justify-between space-x-1">
														<button type="submit" class="btn btn-warning w-26 !text-white" @click="openWarningAlert()">Save as Draft</button>
														<button  class="btn btn-primary w-36"  @click="openInfoAlert()">Save</button>
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
		</div>

		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:infoAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-blue-500 border-8 bg-blue-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></ExclamationTriangleIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-blue-400">Confirm</h2>
									<h5 class="leading-tight">Are you sure you want to revise this JOI?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/job_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<button @click="closeInfoAlert2()" class="btn !bg-blue-500 !text-white btn-sm !rounded-full w-full">Save</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:approveAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-blue-500 border-8 bg-blue-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></ExclamationTriangleIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-blue-400">Confirm</h2>
									<h5 class="leading-tight">Are you sure you want to approve this revision?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/job_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">No</button>
									<a href="/job_issue/view" class="btn !bg-blue-500 !text-white btn-sm !rounded-full w-full">Yes</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:warningAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-yellow-400 border-8 bg-yellow-400 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
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
									<h2 class="mb-2  font-bold text-yellow-400">Success!</h2>
									<h5 class="leading-tight">You have successfully saved a Revised PO as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/job_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<!-- <a href="/job_issue/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a> -->
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>