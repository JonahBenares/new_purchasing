<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{HomeIcon, UserIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Project/Activity <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/project">Project/Activity List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<div class="pt-2">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Project/Activity</label>
									<textarea class="form-control" placeholder="Project/Activity"></textarea>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Remarks</label>
									<textarea class="form-control" placeholder="Remarks"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Target Start Date</label>
									<input type="text" class="form-control" placeholder="Target Start Date" onfocus="(this.type='date')">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Target Completion</label>
									<input type="text" class="form-control" placeholder="Target Completion" onfocus="(this.type='date')">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Actual Start</label>
									<input type="text" class="form-control" placeholder="Actual Start" onfocus="(this.type='date')">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Actual Completion</label>
									<input type="text" class="form-control" placeholder="Actual Completion" onfocus="(this.type='date')">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Estimated Total(Materials)</label>
									<input type="text" class="form-control" placeholder="Estimated Total(Materials)">
								</div>
							</div>
							<div class="col-lg-3 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Duration (# of days)</label>
									<input type="text" class="form-control" placeholder="Duration (# of days)">
								</div>
							</div>

							<div class="col-lg-6 col-md-6">
								<div class="form-group">
									<label class="text-gray-500 m-0" >Status</label>
									<select type="text" class="form-control" >
										<option value="">--Select Status--</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <button class="btn btn-light">Cancel</button> -->
									<button type="submit" class="btn btn-info mr-2 w-44">Update</button>
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