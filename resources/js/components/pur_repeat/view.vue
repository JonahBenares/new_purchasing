<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, Bars4Icon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();

	const drawer_dr = ref(false)
	const drawer_rfd = ref(false)
	const drawer_revise = ref(false)
	const hideModal = ref(true)
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Repeat PO <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_po">Repeat PO</a></li>
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
                        <div class="pt-1" id="printable" >
                            <div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
                                        <span class="text-sm text-gray-700">PO-CENPRI24-1001</span>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
                                        <span class="text-sm text-gray-700">05/16/24</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Supplier: </span>
                                        <span class="text-sm text-gray-700">MF Computer Solutions, Inc.</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Address:</span>
                                        <span class="text-sm text-gray-700">February 16, 2024</span>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Telephone: </span>
                                        <span class="text-sm text-gray-700">(034) 9872-2772</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
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
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
                                                        <td class="p-0">
                                                            <div class="flex">
                                                                <input type="text" class="w-10 bg-white border-r text-center" disabled value="">
                                                                <input type="text" class="w-full bg-white p-1 text-right" disabled value="">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
                                                        <td class="p-1 text-right ">100.00</td>
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
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <table class="table-bordsered !text-xs w-full">
                                                <tr>
                                                    <td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
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
                                                        <div class="flex justify-start space-x-1">
                                                            <span >Price is </span>
                                                            <span>Exclusive of VAT</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">4.</td>
                                                    <td class="align-top  pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span>Payment </span>
                                                            <span> COD</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">5.</td>
                                                    <td class="align-top  pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span>Delivery Time </span>
                                                            <span> Sample</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">6.</td>
                                                    <td class="align-top  pl-1" colspan="2">sample term</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <table class="table-bordsered !text-xs w-full">
                                                <tr>
                                                    <td class="p-1 uppercase" colspan="3">Other Instructions</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">1.</td>
                                                    <td class="px-1" colspan="2">sample instructions</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">2.</td>
                                                    <td class="px-1" colspan="2">sample instructions -Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</td>
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
                                                    <td class="text-center" width="20%">Noted by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Approved by</td>
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
                                    <div class="row my-2 po_buttons"> 
                                        <hr	class="border-dashed">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-between space-x-2">
                                                <div class="flex justify-between">
                                                    <a href="/pur_po/edit" type="submit" class="btn btn-info w-26 !rounded-r-none">Revise PO</a>
                                                    <button class="btn btn-info !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerRevise()">
                                                        <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                    </button>
                                                </div>
                                                <div class="flex justify-between space-x-1">
                                                    <div class="flex justify-between">
                                                        <a href="/pur_disburse/new" class="btn btn-warning !text-white w-26 !rounded-r-none">Print RFD</a>
                                                        <button class="btn btn-warning !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerRFD()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <a href="/pur_dr/new" class="btn btn-warning !text-white w-26 !rounded-r-none">Print DR</a>
                                                        <button class="btn btn-warning !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerDR()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div>
                                                    
                                                    <button type="submit" class="btn btn-primary w-36"  @click="printDiv()">Print PO</button>
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
            enter-active-class="transition ease-out duration-500"
            enter-from-class="opacity-0 "
            enter-to-class="opacity-100 "
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 "
            leave-to-class="opacity-0 scale-95"
        >
            <div class="modal pt-0 px-0 !bg-transparent" :class="{ show:drawer_revise }">
                <div @click="closeModal" class="w-full h-screen fixed bg-transparent"></div>
                <div class="modal__content w-3/12 float-right min-h-[690px]">
                    <div class="row mb-3">
                        <div class="col-lg-12 flex justify-between">
                            <span class="font-bold ">DR List</span>
                            <a href="#" class="text-gray-600" @click="closeModal">
                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                            </a>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="modal_s_items ">
                        <div class="">
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662 (Main)</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r1</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r2</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r3</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r4</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r5</a>
                            <a href="#"  @click="closeModal" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r6 (Current)</a>
                        </div>
                        <!-- <div>
                            <p class="text-center text-sm">No Data</p>
                        </div> -->
                    </div> 
                </div>
            </div>
        </Transition>
        <Transition
            enter-active-class="transition ease-out duration-500"
            enter-from-class="opacity-0 "
            enter-to-class="opacity-100 "
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 "
            leave-to-class="opacity-0 scale-95"
        >
            <div class="modal pt-0 px-0 !bg-transparent" :class="{ show:drawer_dr }">
                <div @click="closeModal" class="w-full h-screen fixed bg-transparent"></div>
                <div class="modal__content w-3/12 float-right min-h-[690px]">
                    <div class="row mb-3">
                        <div class="col-lg-12 flex justify-between">
                            <span class="font-bold ">DR List</span>
                            <a href="#" class="text-gray-600" @click="closeModal">
                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                            </a>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="modal_s_items ">
                        <div class="">
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                        </div>
                        <!-- <div>
                            <p class="text-center text-sm">No Data</p>
                        </div> -->
                    </div> 
                </div>
            </div>
        </Transition>
        <Transition
            enter-active-class="transition ease-out duration-500"
            enter-from-class="opacity-0 "
            enter-to-class="opacity-100 "
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 "
            leave-to-class="opacity-0 scale-95"
        >
            <div class="modal pt-0 px-0 !bg-transparent !rounded-l-none" :class="{ show:drawer_rfd }">
                <div @click="closeModal" class="w-full h-screen fixed bg-tranparent"></div>
                <div class="modal__content w-3/12 float-right min-h-[690px]">
                    <div class="row mb-3">
                        <div class="col-lg-12 flex justify-between">
                            <span class="font-bold ">RFD List</span>
                            <a href="#" class="text-gray-600" @click="closeModal">
                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                            </a>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="modal_s_items ">
                        <div class="">
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                        </div>
                        <!-- <div>
                            <p class="text-center text-sm">No Data</p>
                        </div> -->
                    </div> 
                </div>
            </div>
        </Transition>
	</navigation>
</template>