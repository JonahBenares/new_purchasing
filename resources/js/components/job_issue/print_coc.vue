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
                            <span class="font-bold text-gray-500 text-base">CERTIFICATE OF COMPLETION</span>
                        </div>
                        <hr class="border-dashed mt-0">
                        <div class="pt-1" id="printable">
                            <div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase">CERTIFICATE OF COMPLETION</span>
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
                            <div>
                                
								<div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-20 text-right">Ref: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>	
									</div>
								</div>
								
                                <div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">JO Reference: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>
									</div>
								</div>
                                
                                <div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">Activity: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>
									</div>
                                </div>
                                <br>
								<div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p>	This is to certify that <span class="text-sm text-gray-700 font-bold pr-1 !w-40">(Name of the Supplier)</span> has already completed the following scope of works for:</p>
										</div>
									</div>
								</div>
								<div class="row px-5">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p class="text-sm">
                                                A. Supply of labor, Materials, tools, equipment and technical expertise for the Quarterly Routine Oil Analysis for Predictive Maintenance: June 2024 (Units 1,2,3,4,5, and Black Start DG Set)	<br>
                                                <br>
                                                Scope of work includes:<br><br>

                                                1. Conduct Testing of Lube Oil Sample from:<br>
                                                *Shell Argina S4 SAE40 (6 samples)<br>
                                                a. DG1 Bottom Sump<br>
                                                b. DG2 Bottom Sump<br>
                                                *Shell Rimula R4 15W40 (1 sample)<br>
                                                a. Black Start DG Set<br>
                                                *Petron Petromar HF 4040 (1 sample)<br>
                                                a. DG3 Bottom Sump<br>
                                                2. Submission of Reports with results interpretation.<br><br>


                                                A. Approach<br>
                                                Our oil analysis program incorporates an in-depth analysis of machine wear condition, oil condition, and contamination condition. In addition to that, we will also help you monitor and set limits on oil analysis parameters through trending of the previous tests which will help you know if your equipment is in critical condition. Our oil analysis aims to help customers save their time and money by removing catastrophic failure and unplanned maintenance through accurate results interpretation and action plans focusing on the determination of root causes, resulting in high efficiency, availability, and safety of assets.<br>
                                                Our MachineDiagnostics™ reports have three parts, the Machine Wear Condition, Oil Condition, and
                                                Contamination Condition:<br>
                                                * Machine wear condition analysis involves the analysis of wear metals present in the used oil sample. There could be visible or non – visible particles in the used oil which could be determined through elemental analysis using a combination of RDE (for fine wear metal) and RFS (for coarse wear metal). Those tests mentioned could be used as a tool for determining whether your oil and your machine are at its optimum condition.<br>
                                                * Oil condition analysis involves the analysis of the properties of the oil used for the equipment. The properties include viscosity, oxidation, nitration, total acidic number/total base number. Checking these parameters are essential to determine if your lubricant is still suitable for use or if it must be changed. Using oil, which is highly oxidized, nitrated, or acidic could have detrimental effects to the equipment. Conversely, using oil with extremely high or low viscosities could also damage your equipment.<br>
                                                * Contamination condition analysis involves the analysis of the contaminants present in the oil such as dirt, water, fuel, and wrong oil which could damage a machine resulting to eventual breakdown of the equipment. Dirt is an abrasive contaminant which causes the wearing of equipment. Meanwhile, water could cause corrosion while fuel contamination could decrease oil viscosities.<br>
                                                The strength of our MachineDiagnosticsTM Program lies in our short turn-around time, trending of results, excellent interpretation, and actionable recommendation that will greatly help your equipment run at its optimum performance.<br><br>

                                                B. TEST PACKAGE DESCRIPTION<br>
                                                Upon receipt of the oil sample in the laboratory with complete sample details, a confirmation email from our administrative officer will be sent to the client with a photo of the samples received. Our test methods adhere to internationally accepted ASTM Standards. A short description of each test method is available upon request.<br>
                                                * Test Code: 804<br>
                                                * Test Method:<br>
                                                ASTM D6595<br>
                                                AES-RFS<br>
                                                ASTM E2412<br>
                                                ASTM D445<br>
                                                ASTM D4739<br>
                                                ASTM D3828<br>
                                                ASTM E2412M<br>
                                                * Test Descriptions:<br>
                                                - LubeCheckTM 804<br>
                                                Includes the following individual tests:<br>
                                                • RDE Fine Wear Metal – 18 elements<br>
                                                • RFS Coarse Wear Metal – 11 elements AES-RFS<br>
                                                • Oil Condition (oxidation, nitration)<br>
                                                • Viscosity @100°C<br>
                                                • TBN (Total Base Number)<br>
                                                • Flash Point Method A Closed CupTM Flash/ No Flash<br>
                                                • % Moisture<br>
                                                Report with Interpretation and Recommendation<br>
                                                Sample Volume Required: 100 ml (amount may vary according to the type of oil sample)<br><br>

                                                II. Program Inclusions:<br>
                                                1. Technical Services include – Data interpretation, Recommendation, and Trending over an online database.<br>
                                                2. Samples are being analyzed and interpreted by an ICML-certified MLT/MLA Laboratory Analyst.<br>
                                                3. Laboratory Response for any aftersales concerns.<br>
                                                4. Supply of ISO 3722 super clean sample bottles with oil sample information form (SIF).<br>
                                                5. Free analysis for fresh/new oil sample to have an updated baseline.<br>
                                                6. Web e-lubecheck Account to monitor trending and sample reports database.<br>
                                                7. One (1) free copy of Machinery Lubrication Philippines Magazine every quarter.<br>
                                                8. Free monthly subscription to CRE Newsletters.<br><br>

                                                III. Notes:<br>
                                                1. Turnaround time 5-7 working days upon reaching CRE Lab together with Purchase Order or payment (COD customer only) and complete sample information form.<br>
                                                2. For bulk samples: normal turnaround time applies for the first 30 samples. Additional 1 day TAT for the succeeding 20 samples.<br>
                                                3. Test Reports are electronically generated and transmitted. A separate charge will apply if hard copy of test report is requested.<br>
                                                4. Sample bottles will be provided by CRE Laboratories Corp. with a minimal amount of fee for delivery.<br>
                                            </p>
										</div>
									</div>
								</div>
                                <div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p>The above scope of works was completed and tested by <span class="text-sm text-gray-700 font-bold pr-1 !w-40">(Name of the Supplier)</span> on <input type="date" class="border px-2"> </p>
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<textarea name="" class="w-full border text-sm px-2 py-1" id="" cols="30" rows="2">One (1) year warranty for parts and three (3) months warranty for service.</textarea>
									</div>
								</div>
                                <div class="row">
									<div class="col-lg-12 col-sm-12 col-md-12">
										<div class="flex justify-start">
                                            <p class="text-sm">This certification is being issued on the above-name contractor for payment purposes only.</p>
										</div>
									</div>
								</div>
								<div class="" >
                                    <div class="row mt-4 mb-4">
                                        <div class="col-lg-12">
                                            <table class="w-full text-sm">
                                                <tr>
                                                    <td class="text-left" width="20%">Check and Endorsed by:</td>
                                                    <td width="2%"></td>
                                                    <td class="text-left" width="20%">Approve by:</td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="text-center p-1">
                                                        <select name="" class="py-1   w-full" id="">
                                                            <option value="">Select Employee</option>
                                                        </select>
                                                    </td>
                                                    <td></td>
                                                    <td class="text-center p-1">
                                                        <select name="" class="py-1   w-full" id="">
                                                            <option value="">Select Employee</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center border-b"></td>
                                                    <td></td>
                                                    <td class="text-center border-b"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <hr	class="border-dashed">
                                    
                                    <div class="row my-2 po_buttons" > 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2">
                                                <button type="submit" class="btn btn-primary text-white" @click="printDiv()">Print COC</button>
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