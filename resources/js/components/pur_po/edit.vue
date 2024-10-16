<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
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
		<div class="row"  id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Purchase Order <small>Revise</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_po">Purchase Order</a></li>
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
						<span class="font-bold uppercase text-lg text-center text-yellow-500 print:hidden">CHANGE ORDER FORM</span>
						<hr class="block border-dashed print:hidden">
						<div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase leading_none">Purchase Order</span>
								</div>
								<div class="flex justify-center">
									<span class="uppercase text-xs leading_none text-yellow-500">Change Order Form</span>
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
						<div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 ">
									<span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
									<span class="text-sm text-gray-700">PO-CENPRI24-1001</span>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">05/16/24</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 ">
									<span class="text-sm text-gray-700 font-bold pr-1">Supplier: </span>
									<span class="text-sm text-gray-700">MF Computer Solutions, Inc.</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 ">
									<span class="text-sm text-gray-700 font-bold pr-1">Address:</span>
									<span class="text-sm text-gray-700">February 16, 2024</span>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Telephone: </span>
									<span class="text-sm text-gray-700">(034) 9872-2772</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 ">
									<span class="text-sm text-gray-700 font-bold pr-1">Contact Person: </span>
									<span class="text-sm text-gray-700">Mary Marie</span>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Telefax: </span>
									<span class="text-sm text-gray-700">(034) 9872-2772</span>
								</div>
							</div>
							
							<div class="" >
								<br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="font-bold uppercase text-sm text-gray-500">Current Data</span>
                                        <div class="border-2">
											<table class="table-bordered w-full !text-xs">
												<tr class="bg-gray-100">
													<td class="uppercase p-1 text-center" width="3%">#</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1" colspan="2">Description</td>
													<td class="uppercase p-1 text-center" width="12%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="12%">Total</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1 text-center">1</td>
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">pc</td>
													<td class="border-y-none p-1" colspan="2">Monitor</td>
													<td class="border-y-none p-1 text-right">100.00</td>
													<td class="border-y-none p-1 text-right">500.00</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1 text-center">2</td>
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">pc</td>
													<td class="border-y-none p-1" colspan="2">Mouse</td>
													<td class="border-y-none p-1 text-right">100.00</td>
													<td class="border-y-none p-1 text-right">500.00</td>
												</tr>
												<tr class="">
													<td class="border-y-none p-1 text-center">3</td>
													<td class="border-y-none p-1 text-center">5</td>
													<td class="border-y-none p-1 text-center">pc</td>
													<td class="border-y-none p-1" colspan="2">Keyboard</td>
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
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>PR-19772-8727</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
													</td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
													<td class="p-1 text-right ">200.00</td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
													<td class="p-1 text-right ">200.00</td>
												</tr>
												<tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
                                                        <td class="p-1 text-right ">100.00</td>
                                                    </tr>
                                                    <!-- <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT</td>
                                                        <td class="p-0">
                                                            <div class="flex">
                                                                <input type="text" class="w-10 bg-white border-r text-center" disabled value="12%">
                                                                <input type="text" class="w-10 bg-white border-r text-center" disabled value="12" hidden>
                                                                <input type="text" class="w-full bg-white p-1 text-right" disabled value="">
                                                            </div>
                                                        </td>
                                                    </tr> -->
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">NON-VAT</td>
                                                        <td class="p-0">
                                                            <div class="flex">
                                                                <input type="text" class="w-full bg-white p-1 text-right" disabled value="--">
                                                            </div>
                                                        </td>
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
											<table class="table-bordered w-full !text-xs ">
                                                <!-- <tr>
                                                    <td colspan="7" class=" text-center  font-bold text-yellow-600 p-1 text-sm uppercase">New Data</td>
                                                </tr> -->
												<tr class="bg-yellow-100">
													<td class="uppercase p-1 text-center" width="3%">#</td>
													<td class="uppercase p-1 text-center" width="7%">Qty</td>
													<td class="uppercase p-1 text-center" width="7%">Unit</td>
													<td class="uppercase p-1" colspan="2">Description</td>
													<td class="uppercase p-1 text-center" width="12%">Unit Price</td>
													<td class="uppercase p-1 text-center" width="12%">Total</td>
												</tr>
												<tr class="">
													<td class="align-top  p-1 text-center">1</td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="5">
                                                    </td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="pc">
                                                    </td>
													<td class="align-top leading-none p-0" colspan="2">
                                                        <textarea type="text" class="w-full p-1 leading-tight resize p-1 text-left" name="" id="" value="Monitor" rows="1"></textarea>
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="100.00">
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="500.00">
                                                    </td>
												</tr>
												<tr class="">
													<td class="align-top  p-1 text-center">2</td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="5">
                                                    </td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="pc">
                                                    </td>
													<td class="align-top leading-none p-0" colspan="2">
                                                        <textarea type="text" class="w-full p-1 leading-tight resize p-1 text-left" name="" id="" value="Mouse" rows="1"></textarea>
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="100.00">
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="500.00">
                                                    </td>
												</tr>
                                                <tr class="">
													<td class="align-top  p-1 text-center">3</td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="5">
                                                    </td>
													<td class="align-top ">
                                                        <input type="text" class="w-full  p-1 text-center" name="" id="" value="pc">
                                                    </td>
													<td class="align-top leading-none p-0" colspan="2">
                                                        <textarea type="text" class="w-full p-1 leading-tight resize p-1 text-left" name="" id="" value="KeyBoard" rows="1"></textarea>
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="100.00">
                                                    </td>
													<td class="align-top p-0 text-right">
                                                        <input type="text" class="w-full  p-1 text-right" name="" id="" value="500.00">
                                                    </td>
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
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PR Number:</span>PR-19772-8727</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
														<p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
													</td>
													<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-0.5 text-right pr-1"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right"></td>
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
													<td class="p-0"><input type="text" class="w-full bg-yellow-50 p-1 text-right"></td>
												</tr>
												<!-- <tr class="">
													<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT</td>
													<td class="p-0">
														<div class="flex">
															<input type="text" class="w-10 bg-white border-r text-center" disabled value="12%">
															<input type="text" class="w-10 bg-white border-r text-center" disabled value="12" hidden>
															<input type="text" class="w-full bg-white p-1 text-right" disabled value="">
														</div>
													</td>
												</tr> -->
												<tr class="">
													<td class="border-l-none border-y-none p-0 text-right" colspan="2">
														<div class="flex justify-end">
															<!-- <span class="p-1" >VAT</span> -->
															<select name="" class="border px-1 text-xs" id="">
																<option value="">VAT</option>
																<option value="">NON-VAT</option>
															</select>
														</div>
													</td>
													<!-- Kamo na bahala mag hide sang duwa ka input sa dalom kung Non VAT-->
													<td class="p-0">
														<div class="flex p-0">
															<input type="text" class="w-10 border-r bg-yellow-50 text-center" value="12%">
															<input type="text" class="w-10 border-r bg-yellow-50 text-center" value="12" hidden>
															<input type="text" class="w-full bg-yellow-50 p-1 text-right" value="">
														</div>
													</td>
													<!-- <td class="p-0">
														<div class="flex">
															<input type="text" class="w-full bg-white p-1 text-right" disabled value="--">
														</div>
													</td> -->
												</tr>
												<tr class="">
													<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
													<td class="p-1 text-right font-bold !text-sm">2000.00</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								
								<div class="row mt-2">
									<div class="col-lg-6 col-md-6 col-sm-6">
										<table class="table-bordered !text-xs w-full ">
											<tr>
												<td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
											</tr>
											<tr class="">
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
									<div class="col-lg-6 col-md-6 col-sm-6">
										<table class="table-bordered !text-xs w-full">
											<tr>
												<td class="p-1 uppercase" colspan="3">Other Instructions</td>
											</tr>
											<tr class="">
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
										</table>
									</div>
								</div>
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
												<td class="text-center p-1">Henne Tanant</td>
												<td></td>
												<td class="text-center p-1">Beverly Sy</td>
												<td></td>
												<td class="text-center p-1">Jonah Marie Dy</td>
												<td></td>
												<td class="text-center p-1">Glenn Paulate</td>
											</tr>
											<tr>
												<td class="text-center"><br><br></td>
												<td></td>
												<td class="text-center"></td>
												<td></td>
												<td class="text-center"></td>
											</tr>
											<tr>
												<td class="text-right" colspan="2">Conforme: </td>
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
								<div class="row mt-2 po_buttons">
									<div class="col-lg-12">
										<span class="text-xs">Internal Comment</span>
										<textarea name="" id=""  rows="2" class="w-full bg-yellow-50 text-xs border p-1"></textarea>
									</div>
								</div>
								<hr	class="border-dashed">
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
									<h5 class="leading-tight">Are you sure you want to revise this PO?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
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
									<!-- <a href="/pur_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">No</button>
									<a href="/pur_po/view" class="btn !bg-blue-500 !text-white btn-sm !rounded-full w-full">Yes</a>
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
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<!-- <a href="/pur_po/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a> -->
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>