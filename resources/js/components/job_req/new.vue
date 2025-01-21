<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted, watch } from "vue"
    import { useRouter } from "vue-router"
	const router = useRouter();
	const jo_options = ref();
	let item_list=ref([]);
	let scope_list=ref([]);
	let notes_list=ref([]);
	let department_list=ref([]);
	let form=ref([]);
	let success=ref('');
	let error=ref('');
	let error_pr=ref([]);
	let item_no=ref();
	let qty=ref(0);
	let uom=ref('');
	let pn_no=ref('');
	let item_desc=ref('');
	let wh_stocks=ref(0);
	let date_needed=ref('');
	let recom_date=ref('');
	let scope_item_no=ref();
	let scope_work=ref('');
	let scope_qty=ref(0);
	let scope_uom=ref('');
	let scope_unit_cost=ref(0);
	let scope_total_cost=ref('');
	let scope_recom_date=ref('');
	let notes_item_no=ref();
	let notes=ref("");
	let jorFile=ref("");
	let jorUrl=ref("");
	let check_jor=ref(true)
	let jorheadid=ref(0);
	let jor_head_id=ref(0);
	let jor_labor_details_id=ref(0);
	let jor_material_details_id=ref(0);
	let jor_note_id=ref(0);
	let jorhead=ref([]);
	let jorlabordetails=ref([]);
	let jormaterialdetails=ref([]);
	let jornotes=ref([]);
	let processing_code=ref([]);
	let error_inventory=ref("");
	let recom_date_update_labor=ref([]);
	let recom_date_update_material=ref([]);
	let label=ref();
	const dangerAlerterrors = ref(false)
	const successAlertCD = ref(false)
	let jor_series=ref('0');
	const loading = ref(false)
	let signatories=ref([]);
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		getProcesscode()
		getDepartment()
		getSignatories()
		jorForm()
		if(props.id!=0){
			getImportdata(props.id)
		}
	})
	const getSignatories = async () => {
		let response = await axios.get("/api/get_signatories");
		signatories.value = response.data.employees;
	}
	const getDepartment = async () => {
		let response = await axios.get("/api/get_department");
		department_list.value = response.data.department;
	}
	const getProcesscode = async () => {
		let response = await axios.get("/api/processing_code");
		processing_code.value = response.data;
	}
	const upload_jor = (event) => {
		const btn_jor = document.getElementById("btn_jor");
		btn_jor.disabled = false;
		let file = event.target.files[0];
		if(event.target.files.length===0){
			jorFile.value='';
			jorUrl.value='';
			return;
		}else if(file['size'] < 2111775){
			jorFile.value = event.target.files[0];
			error_inventory.value=''
		}else{
			jorUrl.value='';
			error_inventory.value='File size cannot be bigger than 2 MB'
			dangerAlerterrors.value = !dangerAlerterrors.value
			btn_jor.disabled = true;
		}
	}
	
	watch(jorFile, (jorFile) => {
		if(!(jorFile instanceof File)){
			return;
		}
		let fileReader = new FileReader();
		fileReader.readAsDataURL(jorFile)
		fileReader.addEventListener("load", () => {
			jorUrl.value=fileReader.result
		})
	})

	const importSave = () => {
		const formData= new FormData()
		formData.append('upload_jor',jorFile.value)
		loading.value=true;
		jo_options.value='';
		try {
			axios.post("/api/import_jor",formData).then(function (response) {
				if(response.data!='error' && response.data!='duplicateerror'){
					jor_head_id.value=response.data.jor_head_id
					getImportdata(jor_head_id.value)
					jo_options.value='jo_upload';
					loading.value=false;
					const fileInput = document.getElementById('upload_jor');
					if (fileInput) {
						fileInput.value = ''; // Reset the file input
					}
					jorFile.value=''
					const btn_jor = document.getElementById("btn_jor");
					btn_jor.disabled = true;
				}else if(response.data=='duplicateerror'){
					const fileInput = document.getElementById('upload_jor');
					if (fileInput) {
						fileInput.value = ''; // Reset the file input
					}
					jorFile.value=''
					loading.value=false;
					error.value ='Site JOR No. duplicate entry!';
					dangerAlerterrors.value=!dangerAlerterrors.value
				}else{
					const fileInput = document.getElementById('upload_jor');
					if (fileInput) {
						fileInput.value = ''; // Reset the file input
					}
					jorFile.value=''
					loading.value=false;
					error.value ='The uploaded file did not pass validation. Ensure it meets all requirements and try again.';
					dangerAlerterrors.value=!dangerAlerterrors.value
				}
			}, function (err) {
				loading.value=false;
				var substring="1048 Column 'department_id'"
				var substring1="1048 Column 'requestor_id'"
				var substring2="floor(): Argument #1"
				if(err.response.data.message.includes(substring)==true){
					error.value = 'Department name does not exist, make sure it is existing in deparment masterfile.';
					document.getElementById('upload_jor').value=''
					jorFile.value=''
					jo_options.value='';
					const btn_jor = document.getElementById("btn_jor");
					btn_jor.disabled = true;
				}else if(err.response.data.message.includes(substring1)==true){
					error.value = 'Requestor does not exist, make sure it is existing in employees masterfile.';
					document.getElementById('upload_jor').value=''
					jorFile.value=''
					jo_options.value='';
					const btn_jor = document.getElementById("btn_jor");
					btn_jor.disabled = true;
				}else if(err.response.data.message.includes(substring2)==true){
					error.value = 'Invalid file format. Please upload another file with correct format.';
					document.getElementById('btn_jor').value=''
					jorFile.value=''
					jo_options.value='';
					const btn_jor = document.getElementById("btn_jor");
					btn_jor.disabled = true;
				}else{
					error.value = err.response.data.message;
				}
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		} catch (error) {
			error.value=error
			dangerAlerterrors.value=!dangerAlerterrors.value
		} 
    }

	const getImportdata = async (id) => {
		if(props.id!=0){
			jor_head_id.value=props.id
		}
		let response = await axios.get('/api/get_import_data_jor/'+id);
		jorhead.value = response.data.jor_head;
		jorlabordetails.value = response.data.jor_labor_details;
		jormaterialdetails.value = response.data.jor_material_details;
		jornotes.value = response.data.jor_notes_details;
	}
	const addItem= () => {
		if(qty.value == ''){
			// alert("Quantity must not be empty!")
			document.getElementById('qty_check').placeholder="Quantity must not be empty!"
			document.getElementById('qty_check').style.backgroundColor = '#FAA0A0';
		}else if(uom.value == ''){
			// alert("Uom must not be empty!")
			document.getElementById('uom_check').placeholder="Uom must not be empty!"
			document.getElementById('uom_check').style.backgroundColor = '#FAA0A0';
		}else if(item_desc.value == ''){
			// alert("Item Description must not be empty!")
			document.getElementById('item_check').placeholder="Item Description must not be empty!"
			document.getElementById('item_check').style.backgroundColor = '#FAA0A0';
		}else{
			const items = {
				item_no:item_no.value,
				qty:qty.value,
				uom:uom.value,
				pn_no:pn_no.value,
				item_desc:item_desc.value,
				wh_stocks:wh_stocks.value,
				date_needed:date_needed.value,
				recom_date:recom_date.value,
			}
			// item_list.value.push(items)
			if(item_list.value.length==0 && jormaterialdetails.value.length==0){
				item_list.value.push(items)
			}else{
				const ObjqtyToFind = items.qty;
				const ObjuomToFind = items.uom;
				const Objpn_noToFind = items.pn_no;
				const Objitem_descToFind = items.item_desc;
				const Objwh_stocksToFind = items.wh_stocks;
				const Objdate_neededToFind = items.date_needed;
				const Objrecom_dateToFind = items.recom_date;
				if(jormaterialdetails.value.length==0){
					if (!item_list.value.find((o) => o.qty === ObjqtyToFind) || !item_list.value.find((o) => o.uom === ObjuomToFind) || !item_list.value.find((o) => o.pn_no === Objpn_noToFind) || !item_list.value.find((o) => o.item_desc === Objitem_descToFind) || !item_list.value.find((o) => o.wh_stocks === Objwh_stocksToFind)) {  
						item_list.value.push(items);
					}else{
						error.value="Duplicate entry! This item already exists.";
						dangerAlerterrors.value=!dangerAlerterrors.value
					}
				}else{
					for(var x=0; x<jormaterialdetails.value.length; x++){
						if(((jormaterialdetails.value[x].quantity == items.qty && jormaterialdetails.value[x].uom == items.uom) && jormaterialdetails.value[x].pn_no == items.pn_no &&  jormaterialdetails.value[x].item_description == items.item_desc && jormaterialdetails.value[x].wh_stocks == items.wh_stocks) || (item_list.value.find((o) => o.qty === ObjqtyToFind) && item_list.value.find((o) => o.uom === ObjuomToFind) && item_list.value.find((o) => o.pn_no === Objpn_noToFind) && item_list.value.find((o) => o.item_desc === Objitem_descToFind) && item_list.value.find((o) => o.wh_stocks === Objwh_stocksToFind))){
							var checker = true; 
						}
					}
					if (checker==undefined) {  
						item_list.value.push(items);
					}else{
						error.value="Duplicate entry! This item already exists.";
						dangerAlerterrors.value=!dangerAlerterrors.value
					}
				}
			}
			qty.value='';
			uom.value='';
			pn_no.value='';
			item_desc.value='';
			wh_stocks.value=0;
			date_needed.value='';
			recom_date.value='';
			document.getElementById('qty_check').placeholder=""
			document.getElementById('qty_check').style.backgroundColor = '#FFFFFF';
			document.getElementById('uom_check').placeholder=""
			document.getElementById('uom_check').style.backgroundColor = '#FFFFFF';
			document.getElementById('item_check').placeholder=""
			document.getElementById('item_check').style.backgroundColor = '#FFFFFF';
		}
	}

	const removeItem = (index) => {
		item_list.value.splice(index,1)
		dangerAlert_item.value = !hideAlert.value
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

	const addScope= () => {
		if(scope_work.value == ''){
			// alert("Scope of work must not be empty!")
			document.getElementById('scope_check').placeholder="Scope of work must not be empty!"
			document.getElementById('scope_check').style.backgroundColor = '#FAA0A0';
		}else if(scope_qty.value == '0'){
			// alert("Quantity must not be empty!")
			document.getElementById('scope_qty_check').placeholder="Quantity must not be empty!"
			document.getElementById('scope_qty_check').style.backgroundColor = '#FAA0A0';
		}else if(scope_uom.value == ''){
			// alert("Uom must not be empty!")
			document.getElementById('scope_uom_check').placeholder="UOM must not be empty!"
			document.getElementById('scope_uom_check').style.backgroundColor = '#FAA0A0';
		}
		// else if(scope_unit_cost.value == '0'){
		// 	// alert("Unit cost must not be empty!")
		// 	document.getElementById('scope_unit_cost_check').placeholder="Unit cost must not be empty!"
		// 	document.getElementById('scope_unit_cost_check').style.backgroundColor = '#FAA0A0';
		// }
		else{
			const scope = {
				scope_item_no:scope_item_no.value,
				scope_work:scope_work.value,
				scope_qty:scope_qty.value,
				scope_uom:scope_uom.value,
				scope_unit_cost:scope_unit_cost.value,
				scope_total_cost:scope_total_cost.value,
				scope_recom_date:scope_recom_date.value,
			}
			if(scope_list.value.length==0 && jorlabordetails.value.length==0){
				scope_list.value.push(scope)
			}else{
				if(jorlabordetails.value.length==0){
					for(var x=0; x<scope_list.value.length; x++){
						if(scope_list.value[x].scope_qty == scope.scope_qty && scope_list.value[x].scope_uom == scope.scope_uom && scope_list.value[x].scope_work == scope.scope_work && scope_list.value[x].scope_unit_cost == scope.scope_unit_cost){
							var checker = true; 
						}
					}
					if (checker==undefined) {  
						scope_list.value.push(scope);
					}else{
						error.value="Duplicate entry! This scope already exists.";
						dangerAlerterrors.value=!dangerAlerterrors.value
					}
				}else{
					const ObjqtyToFind = scope.scope_qty;
					const Objscope_uomToFind = scope.scope_uom;
					const Objscope_workToFind = scope.scope_work;
					const Objunit_costToFind = scope.scope_unit_cost;
					for(var x=0; x<jorlabordetails.value.length; x++){
						var text1=jorlabordetails.value[x].scope_of_work
						var text2=scope.scope_work
						text1 = text1.replace(/\s+/g, ' '); // Replace multiple spaces with a single space
						text2 = text2.replace(/\s+/g, ' ');
						if((text1 === text2 && jorlabordetails.value[x].quantity == scope.scope_qty && jorlabordetails.value[x].uom == scope.scope_uom && jorlabordetails.value[x].unit_cost == scope.scope_unit_cost) || (scope_list.value.find((o) => o.scope_qty === ObjqtyToFind) && scope_list.value.find((o) => o.scope_uom === Objscope_uomToFind) && scope_list.value.find((o) => o.scope_work === Objscope_workToFind) && scope_list.value.find((o) => o.scope_unit_cost === Objunit_costToFind))){
							var checker = true; 
						}
					}
					if (checker==undefined) {  
						scope_list.value.push(scope);
					}else{
						error.value="Duplicate entry! This scope already exists.";
						dangerAlerterrors.value=!dangerAlerterrors.value
					}
				}
			}
			scope_qty.value=0;
			scope_uom.value='';
			scope_work.value='';
			scope_unit_cost.value=0;
			scope_total_cost.value=0;
			scope_recom_date.value='';
			document.getElementById('scope_check').placeholder=""
			document.getElementById('scope_check').style.backgroundColor = '#FFFFFF';
			document.getElementById('scope_qty_check').placeholder=""
			document.getElementById('scope_qty_check').style.backgroundColor = '#FFFFFF';
			document.getElementById('scope_uom_check').placeholder=""
			document.getElementById('scope_uom_check').style.backgroundColor = '#FFFFFF';
			document.getElementById('scope_unit_cost_check').placeholder=""
			document.getElementById('scope_unit_cost_check').style.backgroundColor = '#FFFFFF';
		}
	}
	const removeScopeitem = (index) => {
		scope_list.value.splice(index,1)
		dangerAlert_item3.value = !hideAlert.value
	}

	const removeScopeitem1 = () => {
		const scope = document.getElementById("scope");
		dangerAlert_item3.value = !hideAlert.value
		scope.remove();
	}

	const addNotes= () => {
		if(notes.value == ''){
			// alert("Notes must not be empty!")
			document.getElementById('notes_check').placeholder="Notes must not be empty!"
			document.getElementById('notes_check').style.backgroundColor = '#FAA0A0';
		}else{
			const notes1 = {
				notes_item_no:notes_item_no.value,
				notes:notes.value,
			}
			notes_list.value.push(notes1)
			notes.value='';
			document.getElementById('notes_check').placeholder=""
			document.getElementById('notes_check').style.backgroundColor = '#FFFFFF';
		}
	}
	const removeNotes = (index) => {
		notes_list.value.splice(index,1)
		dangerAlert_item4.value = !hideAlert.value
	}

	const removeNotes1 = () => {
		const notes = document.getElementById("notes");
		dangerAlert_item4.value = !hideAlert.value
		notes.remove();
	}

	const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const dangerAlert_item1 = ref(false)
	const dangerAlert_item2 = ref(false)
	const dangerAlert_item3 = ref(false)
	const dangerAlert_item4 = ref(false)
	const successAlert = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
	const openDangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
	const openDangerAlert_item = () => {
		dangerAlert_item.value = !dangerAlert_item.value
	}
	const openDangerAlert_item1 = () => {
		dangerAlert_item1.value = !dangerAlert_item1.value
	}
	const openDangerAlert_item2 = () => {
		dangerAlert_item2.value = !dangerAlert_item2.value
	}
	const openDangerAlert_item3 = () => {
		dangerAlert_item3.value = !dangerAlert_item3.value
	}
	const openDangerAlert_item4 = () => {
		dangerAlert_item4.value = !dangerAlert_item4.value
	}
    const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}

	const openWarningAlert = () => {
		warningAlert.value = !warningAlert.value
	}
	const closeAlert = () => {
		successAlertCD.value = !hideAlert.value
		successAlert.value = !hideAlert.value
		dangerAlerterrors.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		warningAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
		dangerAlert_item.value = !hideAlert.value
		dangerAlert_item1.value = !hideAlert.value
		dangerAlert_item2.value = !hideAlert.value
		dangerAlert_item3.value = !hideAlert.value
		dangerAlert_item4.value = !hideAlert.value
	}

	const generateJorNo = () => {
		const formData= new FormData()
		if(props.id==0){
			if(jorhead.value.jor_no!='' && jorhead.value.jor_no!=undefined){
				formData.append('date_prepared', jorhead.value.date_prepared)
				formData.append('department',  jorhead.value.department_id)
				formData.append('series', jor_series.value)
				formData.append('jor_no',  jorhead.value.jor_no)
			}else{
				formData.append('date_prepared', form.value.date_prepared)
				formData.append('department', form.value.department)
				formData.append('series', jor_series.value)
				formData.append('jor_no', form.value.jor_no)
			}
		}else{
			formData.append('date_prepared', jorhead.value.date_prepared)
			formData.append('department',  jorhead.value.department_id)
			formData.append('series', jor_series.value)
			formData.append('jor_no',  jorhead.value.jor_no)
		}
		formData.append('props_id', props.id)
		axios.post(`/api/generate_jorno`,formData).then(function (response) {
			if(props.id==0){
				if(jorhead.value.jor_no!='' && jorhead.value.jor_no!=undefined){
					jorhead.value.jor_no=response.data
				}else{
					form.value.jor_no=response.data
				}
			}else{
				jorhead.value.jor_no=response.data
			}
		}, function (err) {
			error.value = err.response.data.message;
		});
	}

	const deleteItem = (id,option,name) => {
		if(option=='yes'){
			axios.get(`/api/delete_jor_item/`+id).then(function () {
				label.value=name
				dangerAlert_item1.value = !hideAlert.value
				success.value='Successfully deleted scope!'
				successAlertCD.value = !successAlertCD.value
				getImportdata(jor_head_id.value)
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			label.value=name
			jor_labor_details_id.value=id
			dangerAlert_item1.value = !dangerAlert_item1.value
		}
	}

	const deleteItemMaterial = (id,option,name) => {
		if(option=='yes'){
			axios.get(`/api/delete_jor_material_item/`+id).then(function () {
				label.value=name
				dangerAlert_item1.value = !hideAlert.value
				success.value='Successfully deleted item!'
				successAlertCD.value = !successAlertCD.value
				getImportdata(jor_head_id.value)
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			label.value=name
			jor_material_details_id.value=id
			dangerAlert_item1.value = !dangerAlert_item1.value
		}
	}

	const deleteItemNotes = (id,option,name) => {
		if(option=='yes'){
			axios.get(`/api/delete_jor_note_item/`+id).then(function () {
				label.value=name
				dangerAlert_item1.value = !hideAlert.value
				success.value='Successfully deleted a note!'
				successAlertCD.value = !successAlertCD.value
				getImportdata(jor_head_id.value)
				setTimeout(() => {
					closeAlert()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			label.value=name
			jor_note_id.value=id
			dangerAlert_item1.value = !dangerAlert_item1.value
		}
	}

	const updateRecomdate = (id,identifier) => {
		const formData= new FormData()
		if(identifier=='Labors'){
			if(props.id!=0){
				formData.append('recom_date', JSON.stringify(jorlabordetails.value))
				var api='update_jor_labor_recomdate_view';
			}else{
				formData.append('recom_date', JSON.stringify(recom_date_update_labor.value))
				var api='update_jor_labor_recomdate/'+id;
			}
		}else if(identifier=='Materials'){
			if(props.id!=0){
				formData.append('recom_date', JSON.stringify(jormaterialdetails.value))
				var api='update_jor_material_recomdate_view';
			}else{
				formData.append('recom_date', JSON.stringify(recom_date_update_material.value))
				var api='update_jor_material_recomdate/'+id;
			}
		}
		axios.post('/api/'+api,formData).then(function () {
			recom_date_update_labor.value=[]
			recom_date_update_material.value=[]
			getImportdata(jor_head_id.value)
		}, function (err) {
			error.value = err.response.data.message;
		});
	}

	const onSave = () => {
		const formData= new FormData()
		formData.append('location', jorhead.value.location)
		formData.append('jor_no', jorhead.value.jor_no)
		formData.append('site_jor', jorhead.value.site_jor)
		formData.append('date_prepared', jorhead.value.date_prepared)
		formData.append('duration', jorhead.value.duration)
		formData.append('completion_date', jorhead.value.completion_date)
		formData.append('delivery_date', jorhead.value.delivery_date)
		formData.append('department_id', jorhead.value.department_id)
		formData.append('urgency', jorhead.value.urgency)
		formData.append('process_code', jorhead.value.process_code)
		formData.append('requestor', jorhead.value.requestor_id)
		formData.append('purpose', jorhead.value.purpose)
		formData.append('project_activity', jorhead.value.project_activity)
		formData.append('general_description', jorhead.value.general_description)
		formData.append('jorlabordetails', JSON.stringify(jorlabordetails.value))
		formData.append('scope_list', JSON.stringify(scope_list.value))
		formData.append('jormaterialdetails', JSON.stringify(jormaterialdetails.value))
		formData.append('item_list', JSON.stringify(item_list.value))
		formData.append('jornotes', JSON.stringify(jornotes.value))
		formData.append('notes_list', JSON.stringify(notes_list.value))
		formData.append('props_id', props.id)
		if(jorlabordetails.value.length!=0 || scope_list.value.length!=0 || jormaterialdetails.value.length!=0 || item_list.value.length!=0){
			axios.post(`/api/save_jor_upload/${jor_head_id.value}`,formData).then(function (response) {
				success.value='You have successfully saved new jor.'
				successAlert.value=!successAlert.value
				item_list.value=[]
				scope_list.value=[]
				notes_list.value=[]
				getImportdata(jor_head_id.value)	
			}, function (err) {
				error.value = err.response.data.message;
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}else{
			error.value = 'Items/Scopes cannot be empty, Please fill in data.';
			dangerAlerterrors.value=!dangerAlerterrors.value
		}
    }

	const onSaveDraftUpload = () => {
		const formData= new FormData()
		formData.append('location', jorhead.value.location)
		formData.append('jor_no', jorhead.value.jor_no)
		formData.append('site_jor', jorhead.value.site_jor)
		formData.append('date_prepared', jorhead.value.date_prepared)
		formData.append('duration', jorhead.value.duration)
		formData.append('completion_date', jorhead.value.completion_date)
		formData.append('delivery_date', jorhead.value.delivery_date)
		formData.append('department_id', jorhead.value.department_id)
		formData.append('urgency', jorhead.value.urgency)
		formData.append('process_code', jorhead.value.process_code)
		formData.append('requestor', jorhead.value.requestor_id)
		formData.append('purpose', jorhead.value.purpose)
		formData.append('project_activity', jorhead.value.project_activity)
		formData.append('general_description', jorhead.value.general_description)
		formData.append('jorlabordetails', JSON.stringify(jorlabordetails.value))
		formData.append('scope_list', JSON.stringify(scope_list.value))
		formData.append('jormaterialdetails', JSON.stringify(jormaterialdetails.value))
		formData.append('item_list', JSON.stringify(item_list.value))
		formData.append('jornotes', JSON.stringify(jornotes.value))
		formData.append('notes_list', JSON.stringify(notes_list.value))
		formData.append('props_id', props.id)
		axios.post(`/api/save_jor_upload_draft/${jor_head_id.value}`,formData).then(function (response) {
			success.value='You have successfully draft new jor.'
			warningAlert.value=!warningAlert.value
			item_list.value=[]
			scope_list.value=[]
			notes_list.value=[]
			getImportdata(jor_head_id.value)
		}, function (err) {
			error.value = err.response.data.message;
			dangerAlerterrors.value=!dangerAlerterrors.value
		}); 
    }

	const cancelJor = (option) => {
		if(option=='yes'){
			axios.get(`/api/cancel_jor/`+jor_head_id.value).then(function () {
				dangerAlert.value = !hideAlert.value
				success.value='Successfully cancelled jor!'
				successAlertCD.value = !successAlertCD.value
				setTimeout(() => {
					window.location.reload()
				}, 2000);
			}).catch(function(err){
				success.value=''
				error.value=''
			});
		}else{
			dangerAlert.value = !dangerAlert.value
		}
	}

	const jorForm = async () => {
		if(props.id==0){
			let response = await axios.get("/api/create_jor");
			form.value = response.data;
		}else{
			let response = await axios.get('/api/get_import_data_jor/'+props.id);
			form.value = response.data.jor_head;
		}
	}

	const onSaveManual = () => {
		const formData= new FormData()
		formData.append('location', form.value.jo_request)
		formData.append('jor_no', form.value.jor_no)
		formData.append('site_jor', form.value.site_jor)
		formData.append('duration', form.value.duration)
		formData.append('completion_date', form.value.completion_date)
		formData.append('delivery_date', form.value.delivery_date)
		formData.append('date_prepared', form.value.date_prepared)
		formData.append('department_id', form.value.department)
		formData.append('urgency', form.value.urgency)
		formData.append('process_code', form.value.process_code)
		formData.append('requestor', form.value.requestor)
		formData.append('purpose', form.value.purpose)
		formData.append('project_activity', form.value.project_activity)
		formData.append('general_description', form.value.general_description)
		formData.append('series', jor_series.value)
		formData.append('jorhead_id', jorheadid.value)
		formData.append('scope_list', JSON.stringify(scope_list.value))
		formData.append('item_list', JSON.stringify(item_list.value))
		formData.append('notes_list', JSON.stringify(notes_list.value))
		if(scope_list.value.length!=0){
			// if(scope_list.value.length!=0 && item_list.value.length!=0){
			axios.post(`/api/save_jor_manual`,formData).then(function (response) {
				if(response.data!='error'){
					jorheadid.value=response.data;
					success.value='You have successfully saved new jor.'
					successAlert.value=!successAlert.value
				}else{
					error.value ='Site JOR No. duplicate entry!';
					dangerAlerterrors.value=!dangerAlerterrors.value
				}
			}, function (err) {
				error.value=''
				error_pr.value=[]
				if (err.response.data.errors.jor_no) {
					error_pr.value.push(err.response.data.errors.jor_no[0])
				}
				if (err.response.data.errors.department_id) {
					error_pr.value.push(err.response.data.errors.department_id[0])
				}
				if (err.response.data.errors.location) {
					error_pr.value.push(err.response.data.errors.location[0])
				}
				if (err.response.data.errors.date_prepared) {
					error_pr.value.push(err.response.data.errors.date_prepared[0])
				}
				if (err.response.data.errors.requestor) {
					error_pr.value.push(err.response.data.errors.requestor[0])
				}
				if (err.response.data.errors.purpose) {
					error_pr.value.push(err.response.data.errors.purpose[0])
				}
				if (err.response.data.errors.general_description) {
					error_pr.value.push(err.response.data.errors.general_description[0])
				}
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}else{
			error.value=''
			error.value = 'Scopes cannot be empty, Please fill in data.';
			// error.value = 'Scopes/Items cannot be empty, Please fill in data.';
			dangerAlerterrors.value=!dangerAlerterrors.value
		}
    }

	const onSaveDraftManual = () => {
		const formData= new FormData()
		formData.append('location', form.value.jo_request)
		formData.append('jor_no', form.value.jor_no)
		formData.append('site_jor', form.value.site_jor)
		formData.append('duration', form.value.duration)
		formData.append('completion_date', form.value.completion_date)
		formData.append('delivery_date', form.value.delivery_date)
		formData.append('date_prepared', form.value.date_prepared)
		formData.append('department_id', form.value.department)
		formData.append('urgency', form.value.urgency)
		formData.append('process_code', form.value.process_code)
		formData.append('requestor', form.value.requestor)
		formData.append('purpose', form.value.purpose)
		formData.append('project_activity', form.value.project_activity)
		formData.append('general_description', form.value.general_description)
		formData.append('series', jor_series.value)
		formData.append('jorhead_id', jorheadid.value)
		formData.append('scope_list', JSON.stringify(scope_list.value))
		formData.append('item_list', JSON.stringify(item_list.value))
		formData.append('notes_list', JSON.stringify(notes_list.value))
		axios.post(`/api/save_jor_manual_draft`,formData).then(function (response) {
			// console.log(response.data)
			if(response.data!='error'){
				success.value='You have successfully saved new jor.'
				warningAlert.value=!warningAlert.value
				jorheadid.value=response.data;
				const btn_draft = document.getElementById("btn_draft");
				btn_draft.disabled = true;
				setTimeout(() => {
					btn_draft.disabled = false;
				}, 500);
			}else{
				error.value ='Site JOR No. duplicate entry!';
				dangerAlerterrors.value=!dangerAlerterrors.value
			}
		}, function (err) {
			error_pr.value=[]
			// error_pr.value = err.response.data.message;
			if (err.response.data.errors.jor_no) {
				error_pr.value.push(err.response.data.errors.jor_no[0])
			}
			if (err.response.data.errors.department_id) {
				error_pr.value.push(err.response.data.errors.department_id[0])
			}
			dangerAlerterrors.value=!dangerAlerterrors.value
		}); 
    }

	const isNumber = (evt)=> {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode == 46) {
			//Check if the text already contains the . character
			if (evt.target.value.indexOf('.') === -1) {
				return true;
			} else {
				evt.preventDefault();
			}
		} else {
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				evt.preventDefault();
		}
		return true;
    }
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
						<h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600" v-if="jorhead.status=='Draft' && props.id!=0">JO Request <small>Draft</small></h3>
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600" v-else>JO Request <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_req">JO Request</a></li>
                            <li class="breadcrumb-item active" aria-current="page" v-if="jorhead.status=='Draft' && props.id!=0">Draft</li>
                            <li class="breadcrumb-item active" aria-current="page" v-else>New</li>
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
							<div class="col-lg-5 offset-lg-1 col-md-3">
								<div class="form-group">
								<label class="text-gray-500 m-0" for="">Import JOR Excel File here or Manual encode below</label>
								<input type="file" name="img[]" class="file-upload-default">
								<div class="input-group col-xs-12">
									<input type="file" class="form-control file-upload-info" id="upload_jor" name="upload_jor" @change="upload_jor" placeholder="Upload Image">
									<span class="input-group-append">
										<button class="btn btn-primary" :disabled="check_jor" id="btn_jor" @click="importSave()" v-on:click="jo_options = ''">Upload</button>
									</span>
								</div>
								</div>
							</div>
							<div class="col-lg-1">
								<span class="text-center w-full block text-lg mt-4 text-gray-400">OR</span>
							</div>
							<div class="col-lg-4 col-md-3">
								<div class="form-group m-0">
									<label class="text-gray-500 m-0" for="">Add JOR and Items Manually</label>
								</div>
								<button class="btn btn-primary btn-block" type="button" v-on:click="jo_options = 'jo_manual'" v-if="jorhead.status=='Draft' && props.id!=0" disabled>Manual JOR</button>
								<button class="btn btn-primary btn-block" type="button" v-on:click="jo_options = 'jo_manual'" v-else>Manual JOR</button>
							</div>
						</div>
						<div class="mt-[300px] font-bold text-base" v-if="loading"><center>Loading data, please wait...</center></div>
						<div class="" id="upload" v-if="jo_options === 'jo_upload' || props.id!=0">
							<hr class="border-dashed">
							<p class="text-gray-500 font-bold text-lg" v-if="jorhead.status!='Draft'">From Uploaded File</p>
							<p class="text-gray-500 font-bold text-lg" v-else>DRAFT</p>
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Location</label>
										<input type="text" class="form-control" placeholder="Location" v-model="jorhead.location">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">JOR No</label>
										<input type="text" class="form-control" placeholder="JOR No" v-model="jorhead.jor_no" readonly>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Site JOR No</label>
										<input type="text" class="form-control" placeholder="Site JOR No" v-model="jorhead.site_jor">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Date Prepared</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date Prepared" v-model="jorhead.date_prepared">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Department</label>
										<select class="form-control" v-model="jorhead.department_id" @change="generateJorNo()">
											<option value=''>--Select Department--</option>
											<option :value="dept.id" v-for="dept in department_list" :key="dept.id">{{ dept.department_name }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-1 col-md-1">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Urgency</label>
										<input type="text" class="form-control" placeholder="" v-model="jorhead.urgency">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Process Code</label>
										<select class="form-control" v-model="jorhead.process_code">
											<option value=''>Select Process Code</option>
											<option :value="pro" v-for="pro in processing_code" :key="pro">{{ pro }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Duration</label>
										<input type="text" class="form-control" placeholder="" v-model="jorhead.duration">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Requestor</label>
										<!-- <input type="text" class="form-control" placeholder="Requestor"  v-model="jorhead.requestor"> -->
										<select class="form-control" v-model="jorhead.requestor_id">
											<option value='0'>--Select Requestor--</option>
											<option :value="req.id" v-for="req in signatories" :key="req.id">{{ req.name }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Completion Date</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Completion Date" v-model="jorhead.completion_date">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Delivery Date</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Delivery Date" v-model="jorhead.delivery_date">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Purpose</label>
										<input type="text" class="form-control" placeholder="Purpose"  v-model="jorhead.purpose">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Project/Activity</label>
										<textarea class="form-control !font-bold"  v-model="jorhead.project_activity"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">General Description</label>
										<textarea class="form-control !font-bold"  v-model="jorhead.general_description"></textarea>
									</div>
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
											<td class="p-1 uppercase" width="10%">Recom Date</td>
											<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
												<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
											</td>
										</tr>
										<tr>
											<td class="p-1 text-center align-top">#</td>
											<td class="p-0 align-top"><textarea class="w-full p-1 resize" v-model="scope_work" id="scope_check"></textarea></td>
											<td class="p-0 align-top"><input type="number" step="any" class="w-full p-1 text-center" id="scope_qty_check" placeholder="00" v-model="scope_qty"  min="0" @keypress="isNumber($event)"></td>
											<td class="p-0 align-top"><input type="text" id="scope_uom_check" class="w-full p-1 text-center" placeholder="lot" v-model="scope_uom"></td>
											<td class="p-0 align-top"><input type="number" step="any" id="scope_unit_cost_check" class="w-full p-1 text-center" placeholder="00" v-model="scope_unit_cost" min="0" @keypress="isNumber($event)"></td>
											<td class="p-0 align-top"><input type="text" class="w-full p-1 text-center" placeholder="00" :value="scope_qty * scope_unit_cost" readonly disabled></td>
											<td class="p-1"><input placeholder="Recom Date" type="text" v-model="scope_recom_date" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="text-center">
												<button class="btn btn-primary p-1" @click="addScope">
													<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(jl,index) in jorlabordetails" id="scope">
											<td class="p-1 align-top text-center">{{ index + 1 }}</td>
											<td class="p-1 align-top">
												<textarea type="text" v-model="jl.scope_of_work" class="w-full p-1 bg-yellow-50"></textarea>
											</td>
											<td class="p-1 align-top text-center">
												<input type="text" @keypress="isNumber($event)" v-model="jl.quantity" class="w-full p-1 bg-yellow-50 text-center">
											</td>
											<td class="p-1 align-top text-center">
												<input type="text" v-model="jl.uom" class="w-full p-1 text-center bg-yellow-50">
											</td>
											<td class="p-1 align-top text-center">
												<input type="text" v-model="jl.unit_cost" class="w-full p-1 text-center bg-yellow-50">
											</td>
											<td class="p-1 align-top text-center">{{ jl.unit_cost * jl.quantity  }}</td>
											<!-- <td class="p-1 align-top text-center">{{ jl.total_cost  }}</td> -->
											<td class="p-1">
												<input placeholder="Recom Date" type="text" v-model="recom_date_update_labor[index]" class="w-full p-1" onfocus="(this.type='date')" @change="updateRecomdate(jl.id,'Labors')" v-if="props.id==0 && (jl.recom_date==null || jl.recom_date=='')">

												<input @change="updateRecomdate(jl.id,'Labors')" placeholder="Recom Date" type="text" v-model="jl.recom_date" class="w-full p-1" onfocus="(this.type='date')" v-else-if="props.id==0 && (jl.recom_date!=null || jl.recom_date!='')" readonly>
												
												<input placeholder="Recom Date" type="text" v-model="jl.recom_date" class="w-full p-1" onfocus="(this.type='date')" @change="updateRecomdate(jl.id,'Labors')" v-else>
											</td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="deleteItem(jl.id,'no','scope')">
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(x,indexes) in scope_list">
											<td class="p-1 align-top text-center">{{ indexes + 1 + jorlabordetails.length }}</td>
											<td class="p-1 align-top">{{ x.scope_work }}</td>
											<td class="p-1 align-top text-center">{{ x.scope_qty }}</td>
											<td class="p-1 align-top text-center">{{ x.scope_uom }}</td>
											<td class="p-1 align-top text-center">{{ x.scope_unit_cost }}</td>
											<td class="p-1 align-top text-center">{{ x.scope_unit_cost * x.scope_qty  }}</td>
											<!-- <td class="p-1 align-top text-center">{{ x.scope_unit_cost * x.scope_qty  }}</td> -->
											<td class="p-1"><input type="date" class="w-full" v-model="x.scope_recom_date"></td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="removeScopeitem(indexes)">
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
											<td class="p-1 uppercase text-center" width="5%">#</td>
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
											<td class="p-1 uppercase" width="10%">Recom Date</td>
											<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
												<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
											</td>
										</tr>
										<tr>
											<td class=""><input placeholder="#" type="text" v-model="item_no" class="w-full p-1 text-center" disabled></td>
											<td class=""><input placeholder="Qty" id="qty_check" type="text" v-model="qty" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" class="w-full p-1 text-center"></td>
											<td class=""><input placeholder="UOM" id="uom_check" type="text" v-model="uom" class="w-full p-1 text-center"></td>
											<td class=""><input placeholder="PN No." type="text" v-model="pn_no" class="w-full p-1"></td>
											<td class=""><input placeholder="Item Description" id="item_check" v-model="item_desc" type="text" class="w-full p-1"></td>
											<td class=""><input placeholder="WH Stock" type="text" v-model="wh_stocks" class="w-full p-1" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"></td>
											<td class=""><input placeholder="Date Needed" type="text" v-model="date_needed" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="p-1"><input placeholder="Recom Date" type="text" v-model="recom_date" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="text-center">
												<button class="btn btn-primary p-1" @click="addItem">
													<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(jm,indexed) in jormaterialdetails" id="item1">
											<td class="p-1 text-center">{{ indexed + 1 }}</td>
											<td class="p-1 text-center">
												<input type="text" @keypress="isNumber($event)" v-model="jm.quantity" class="w-full bg-yellow-50 p-1 text-center">
											</td>
											<td class="p-1 text-center">
												<input type="text" v-model="jm.uom" class="w-full p-1 bg-yellow-50 text-center">
											</td>
											<td class="p-1">
												<!-- {{ jm.pn_no }} -->
												<input type="text" v-model="jm.pn_no" class="w-full p-1 bg-yellow-50">
											</td>
											<td class="p-1">
												<input type="text" v-model="jm.item_description" class="w-full p-1 bg-yellow-50">
											</td>
											<td class="p-1">
												<input type="text" @keypress="isNumber($event)" v-model="jm.wh_stocks" class="w-full p-1 bg-yellow-50">
											</td>
											<td class="p-1">
												<input type="text"  placeholder="Date Needed"  onfocus="(this.type='date')" v-model="jm.date_needed" class="w-full p-1 bg-yellow-50">
											</td>
											<td class="p-1">
												<input placeholder="Recom Date" type="text" v-model="recom_date_update_material[indexed]" class="w-full p-1" onfocus="(this.type='date')" @change="updateRecomdate(jm.id,'Materials')" v-if="props.id==0  && (jm.recom_date==null || jm.recom_date=='')">
												
												<input @change="updateRecomdate(jm.id,'Materials')" placeholder="Recom Date" type="text" v-model="jm.recom_date" class="w-full p-1" onfocus="(this.type='date')" v-else-if="props.id==0 && jm.recom_date!=null" readonly>

												<input placeholder="Recom Date" type="text" v-model="jm.recom_date" class="w-full p-1" onfocus="(this.type='date')" @change="updateRecomdate(jm.id,'Materials')" v-else>
											</td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="deleteItemMaterial(jm.id,'no','item')">
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(i,indexer) in item_list">
											<td class="p-1 text-center">{{ indexer + 1 + jormaterialdetails.length }}</td>
											<td class="p-1 text-center">{{ i.qty }}</td>
											<td class="p-1 text-center">{{ i.uom }}</td>
											<td class="p-1">{{ i.pn_no }}</td>
											<td class="p-1">{{ i.item_desc }}</td>
											<td class="p-1">{{ i.wh_stocks }}</td>
											<td class="p-1">{{ i.date_needed }}</td>
											<td class="p-1"><input type="date" class="w-full" v-model="i.recom_date"></td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="removeItem(indexer)">
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
											<td class="p-1 uppercase" width="">Notes</td>
											<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
												<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
											</td>
										</tr>
										<tr>
											<td class="p-1 text-center align-top">#</td>
											<td class="p-0 align-top"><textarea placeholder="" id="notes_check" class="w-full p-1 resize" rows="1" v-model="notes"></textarea></td>
											<td class="text-center">
												<button class="btn btn-primary p-1" @click="addNotes">
													<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(jn,indexer) in jornotes" id="notes">
											<td class="p-1 text-center align-top">{{ indexer + 1 }}</td>
											<td class="p-1 align-top">{{ jn.notes }}</td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="deleteItemNotes(jn.id,'no','note')">
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(n,indexers) in notes_list">
											<td class="p-1 text-center align-top">{{ indexers + 1 + jornotes.length }}</td>
											<td class="p-1 align-top">{{ n.notes }}</td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="removeNotes(indexers)">
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
										<!-- <button class="btn btn-light">Cancel</button> -->
										<!-- <button type="submit" class="btn btn-danger mr-2 w-36" v-on:click="jo_options = ''">Cancel</button> -->
										<button type="button" class="btn btn-danger mr-2 w-36" @click="cancelJor('no')">Cancel</button>
										<button type="button" class="btn btn-warning text-white mr-2 w-36" @click="onSaveDraftUpload()">Save as Draft</button>
										<button type="button" class="btn btn-primary mr-2 w-44" @click="onSave()">Save</button>
									</div>
								</div>
							</div>
						</div>
						<div class="" id="manual" v-else-if="jo_options === 'jo_manual'">
							<hr class="border-dashed">
							<p class="text-gray-500 font-bold text-lg">Add Manual JOR</p>
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Location</label>
										<input type="text" class="form-control" placeholder="Location" v-model="form.jo_request">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">JOR No</label>
										<input type="text" class="form-control" placeholder="JOR No" v-model="form.jor_no" readonly>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Site JOR No</label>
										<input type="text" class="form-control" placeholder="Site JOR No" v-model="form.site_jor">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Date Prepared</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date Prepared" v-model="form.date_prepared">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Department</label>
										<select class="form-control" v-model="form.department" @change="generateJorNo()">
											<option value=''>--Select Department--</option>
											<option :value="dept.id" v-for="dept in department_list" :key="dept.id">{{ dept.department_name }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-1 col-md-1">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Urgency</label>
										<input type="text" class="form-control" placeholder="" v-model="form.urgency">
									</div>
								</div>
								<div class="col-lg-2 col-md-2">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Process Code</label>
										<select class="form-control" v-model="form.process_code">
											<option value=''>Select Process Code</option>
											<option :value="pro" v-for="pro in processing_code" :key="pro">{{ pro }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Duration</label>
										<input type="text" class="form-control" placeholder="" v-model="form.duration">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Requestor</label>
										<!-- <input type="text" class="form-control" placeholder="Requestor"  v-model="form.requestor"> -->
										<select class="form-control" v-model="form.requestor">
											<option value='0'>--Select Requestor--</option>
											<option :value="req.id" v-for="req in signatories" :key="req.id">{{ req.name }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Completion Date</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Completion Date" v-model="form.completion_date">
									</div>
								</div>
								<div class="col-lg-3 col-md-3">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Delivery Date</label>
										<input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Delivery Date" v-model="form.delivery_date">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Purpose</label>
										<input type="text" class="form-control" placeholder="Purpose"  v-model="form.purpose">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Project/Activity</label>
										<textarea class="form-control !font-bold"  v-model="form.project_activity"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-6">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">General Description</label>
										<textarea class="form-control !font-bold"  v-model="form.general_description"></textarea>
									</div>
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
											<td class="p-1 uppercase" width="10%">Recom Date</td>
											<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
												<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
											</td>
										</tr>
										<tr>
											<td class="p-1 text-center align-top">#</td>
											<td class="p-0 align-top"><textarea class="w-full p-1 resize" v-model="scope_work" id="scope_check"></textarea></td>
											<td class="p-0 align-top"><input type="number" step="any" min="0" class="w-full p-1 text-center" id="scope_qty_check" placeholder="00" v-model="scope_qty" @keypress="isNumber($event)"></td>
											<td class="p-0 align-top"><input type="text" id="scope_uom_check" class="w-full p-1 text-center" placeholder="lot" v-model="scope_uom"></td>
											<td class="p-0 align-top"><input type="number" step="any" min="0" @keypress="isNumber($event)" id="scope_unit_cost_check" class="w-full p-1 text-center" placeholder="00" v-model="scope_unit_cost"></td>
											<td class="p-0 align-top"><input type="text" class="w-full p-1 text-center" placeholder="00" :value="scope_qty * scope_unit_cost" readonly disabled></td>
											<td class="p-1"><input placeholder="Recom Date" type="text" v-model="scope_recom_date" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="text-center">
												<button class="btn btn-primary p-1" @click="addScope">
													<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(x,indexes) in scope_list">
											<td class="p-1 align-top text-center">{{ indexes + 1 }}</td>
											<td class="p-1 align-top">{{ x.scope_work }}</td>
											<td class="p-1 align-top text-center">{{ x.scope_qty }}</td>
											<td class="p-1 align-top text-center">{{ x.scope_uom }}</td>
											<td class="p-1 align-top text-center">{{ x.scope_unit_cost }}</td>
											<td class="p-1 align-top text-center">{{ x.scope_unit_cost * x.scope_qty  }}</td>
											<td class="p-1"><input type="date" class="w-full" v-model="x.scope_recom_date"></td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="removeScopeitem(indexes)">
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
											<td class="p-1 uppercase" width="10%">Recom Date</td>
											<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
												<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
											</td>
										</tr>
										<tr>
											<td class=""><input placeholder="#" type="text" v-model="item_no" class="w-full p-1 text-center" disabled></td>
											<td class=""><input placeholder="Qty" id="qty_check" type="number" step="any" min="0" @keypress="isNumber($event)" v-model="qty" class="w-full p-1 text-center"></td>
											<td class=""><input placeholder="UOM" id="uom_check" type="text" v-model="uom" class="w-full p-1 text-center"></td>
											<td class=""><input placeholder="PN No." type="text" v-model="pn_no" class="w-full p-1"></td>
											<td class=""><input placeholder="Item Description" id="item_check" v-model="item_desc" type="text" class="w-full p-1"></td>
											<td class=""><input placeholder="WH Stock" type="number" step="any" min="0" @keypress="isNumber($event)" v-model="wh_stocks" class="w-full p-1"></td>
											<td class=""><input placeholder="Date Needed" type="text" v-model="date_needed" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="p-1"><input placeholder="Recom Date" type="text" v-model="recom_date" class="w-full p-1" onfocus="(this.type='date')"></td>
											<td class="text-center">
												<button class="btn btn-primary p-1" @click="addItem">
													<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(i,index) in item_list">
											<td class="p-1 text-center">{{ index + 1 }}</td>
											<td class="p-1 text-center">{{ i.qty }}</td>
											<td class="p-1 text-center">{{ i.uom }}</td>
											<td class="p-1">{{ i.pn_no }}</td>
											<td class="p-1">{{ i.item_desc }}</td>
											<td class="p-1">{{ i.wh_stocks }}</td>
											<td class="p-1">{{ i.date_needed }}</td>
											<td class="p-1"><input type="date" class="w-full" v-model="i.recom_date"></td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="removeItem(index)" >
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
											<td class="p-1 uppercase" width="">Notes</td>
											<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
												<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
											</td>
										</tr>
										<tr>
											<td class="p-1 text-center align-top">#</td>
											<td class="p-0 align-top"><textarea placeholder="" id="notes_check" class="w-full p-1 resize" rows="1" v-model="notes"></textarea></td>
											<td class="text-center">
												<button class="btn btn-primary p-1" @click="addNotes">
													<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
												</button>
											</td>
										</tr>
										<tr v-for="(n,indexe) in notes_list">
											<td class="p-1 text-center align-top">{{ indexe + 1 }}</td>
											<td class="p-1 align-top">{{ n.notes }}</td>
											<td class="text-center">
												<button class="btn btn-danger p-1" @click="removeNotes(indexe)">
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
										<!-- <button class="btn btn-light">Cancel</button> -->
										<button type="button" id="btn_draft" class="btn btn-warning text-white mr-2 w-36" @click="onSaveDraftManual()">Save as Draft</button>
										<button type="button" class="btn btn-primary mr-2 w-44" @click="onSaveManual()">Save</button>
									</div>
								</div>
							</div>
						</div>
						<div v-else></div>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-green-500 border-8 bg-green-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
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
									<h2 class="mb-2  font-bold text-green-400">Success!</h2>
									<h5 class="leading-tight">You have successfully created a new JOR.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/job_req/new/0" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a>
									<a :href="'/job_quote/new/'+jor_head_id" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" v-if="jorheadid==0">Proceed</a>
									<a :href="'/job_quote/new/'+jorheadid" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" v-else>Proceed</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlertCD }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-green-500 border-8 bg-green-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
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
									<h2 class="mb-2  font-bold text-green-400">Success!</h2>
									<h5 class="leading-tight">{{ success }}</h5>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlerterrors }">
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Error!</h2>
									<h5 class="leading-tight" v-if="error!=''" >{{ error }}</h5>
									<h5 class="leading-tight" v-else-if="error_inventory!=''">{{ error_inventory }}</h5>
									<h5 class="leading-tight" v-else-if="error_pr!=''" v-for="er in error_pr">{{ er }}</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeAlert()">Close</button>
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
									<h5 class="leading-tight">You have successfully saved a JOR as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/job_req/new/0" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
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
									<h5 class="leading-tight">Are you sure you want to cancel this JOR?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="cancelJor('yes')">Yes</button>
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
									<h5 class="leading-tight">Are you sure you want to remove this {{ label }}?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="deleteItem(jor_labor_details_id,'yes','scope')" v-if="label=='scope'">Yes</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="deleteItemMaterial(jor_material_details_id,'yes','item')" v-else-if="label=='item'">Yes</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="deleteItemNotes(jor_note_id,'yes','note')" v-else-if="label=='note'">Yes</button>
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