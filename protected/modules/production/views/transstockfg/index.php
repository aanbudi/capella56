<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'transstockid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'ispurge'=>0,
	'isupload'=>0,
	'wfapp'=>'appts',
	'url'=>Yii::app()->createUrl('production/transstockfg/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/transstockfg/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/transstockfg/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/transstockfg/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/transstockfg/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('production/transstockfg/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('production/transstockfg/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/transstockfg/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/transstockfg/downxls'),
	'columns'=>"
		{
			field:'transstockid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appts')."
		}},
		{
			field:'companyname',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plantcode') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('addressbook') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'transstockdate',
			title:'".GetCatalog('transstockdate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'transstockno',
			title:'".GetCatalog('transstockno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productoutputid',
			title:'".GetCatalog('productoutputno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.productoutputno;
		}},
		{
			field:'productplanno',
			title:'".GetCatalog('productplanno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'sono',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sono;
		}},
		{
			field:'slocfromid',
			title:'".GetCatalog('slocfrom') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.slocfromcode;
		}},
		{
			field:'sloctoid',
			title:'".GetCatalog('slocto') ."',
			sortable: true,
			width:'130px',
			formatter: function(value,row,index){
				return row.sloctocode;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.recordstatusname;
		}},",
	'searchfield'=> array ('transstockid','plantcode','transstockdate','transstockno','sono','productplanno','productoutputno','slocfromcode','sloctocode','productname','headernote','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('transstockno')."</td>
				<td><input class='easyui-textbox' id='transstockfg-transstockno' name='transstockfg-transstockno' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('transstockdate')."</td>
				<td><input class='easyui-datebox' type='text' id='transstockfg-transstockdate' name='transstockfg-transstockdate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='transstockfg-plantid' name='transstockfg-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
								method: 'get',
								onHidePanel: function(){
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'plantid':$('#transstockfg-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#transstockfg-companyname').textbox('setValue',data.companyname);
											var g = $('#transstockfg-sloctoid').combogrid('grid');
											var h = $('#transstockfg-productoutputid').combogrid('grid');
											g.datagrid({queryParams: {
												plantid: data.plantid
											}});
											h.datagrid({queryParams: {
												plantid: data.plantid
											}});
										},
										'cache':false});
								},
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."',width:'50px'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."',width:'100px'},
										{field:'description',title:'".getCatalog('description') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('productoutput')."</td>
				<td>
					<select class='easyui-combogrid' id='transstockfg-productoutputid' name='transstockfg-productoutputid' style='width:250px' data-options=\"
						panelWidth: '950px',
						required: true,
						idField: 'productoutputid',
						textField: 'productoutputno',
						mode:'remote',
						url: '".Yii::app()->createUrl('production/productoutput/index',array('grid'=>true,'opts'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#transstockfg-productoutputid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#transstockfg-plantid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('production/productoutput/index',array('grid'=>true,'getdata'=>true)) ."',
								'data':{
									'productoutputid':$('#transstockfg-productoutputid').combogrid('getValue'),
									'sloctoid':$('#transstockfg-sloctoid').combogrid('getValue'),
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#transstockfg-slocfromid').combogrid('setValue',data.slocfromid);
									$('#transstockfg-productplanno').textbox('setValue',data.productplanno);
									$('#transstockfg-sono').textbox('setValue',data.sono);
									$('#transstockfg-fullname').textbox('setValue',data.fullname);
									$('#transstockfg-headernote').textbox('setValue',data.headernote);
									jQuery.ajax({'url':'".Yii::app()->createUrl('production/transstockfg/generatedetail') ."',
										'data':{
											'id':$('#transstockfg-productoutputid').combogrid('getValue'),
											'hid':$('#transstockfg-transstockid').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-transstockfg-transstockdet').datagrid('reload');
										},
										'cache':false});
								},
								'cache':false});
						},
						columns: [[
								{field:'productoutputid',title:'".GetCatalog('productoutputid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('customer') ."',width:'300px'},
								{field:'productoutputno',title:'".GetCatalog('productoutputno') ."',width:'150px'},
								{field:'sono',title:'".GetCatalog('sono') ."',width:'150px'},
								{field:'productplanno',title:'".GetCatalog('productplanno') ."',width:'150px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('productplanno')."</td>
				<td><input class='easyui-textbox' id='transstockfg-productplanno' name='transstockfg-productplanno' data-options='disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('sono')."</td>
				<td><input class='easyui-textbox' id='transstockfg-sono' name='transstockfg-sono' data-options='disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td><input class='easyui-textbox' id='transstockfg-fullname' name='transstockfg-fullname' data-options='disabled:true'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('slocfrom')."</td>
				<td>
					<select class='easyui-combogrid' id='transstockfg-slocfromid' name='transstockfg-slocfromid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						readonly:true,
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indextrxplant',array('grid'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'slocid',title:'".GetCatalog('slocid') ."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'150px'},
								{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('slocto')."</td>
				<td>
					<select class='easyui-combogrid' id='transstockfg-sloctoid' name='transstockfg-sloctoid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'slocid',
						textField: 'sloccode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/sloc/indextrxplant',array('grid'=>true)) ."',
						onBeforeLoad:function(param) {
							param.plantid = $('#transstockfg-plantid').combogrid('getValue')
						},
						method: 'get',
						columns: [[
								{field:'slocid',title:'".GetCatalog('slocid') ."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'150px'},
								{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('headernote')."</td>
				<td><input class='easyui-textbox' id='transstockfg-headernote' name='transstockfg-headernote' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#transstockfg-transstockdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'loadsuccess' => "
		$('#transstockfg-transstockno').textbox('setValue',data.transstockno);
		$('#transstockfg-transstockdate').datebox('setValue',data.transstockdate);
		$('#transstockfg-plantid').combogrid('setValue',data.plantid);
		$('#transstockfg-productoutputid').combogrid('setValue',data.productoutputid);
		$('#transstockfg-productplanno').textbox('setValue',data.productplanno);
		$('#transstockfg-sono').textbox('setValue',data.sono);
		$('#transstockfg-fullname').textbox('setValue',data.fullname);
		$('#transstockfg-slocfromid').combogrid('setValue',data.slocfromid);
		$('#transstockfg-sloctoid').combogrid('setValue',data.sloctoid);
		$('#transstockfg-headernote').textbox('setValue',data.headernote);
	",
	'columndetails'=> array (
		array(
			'id'=>'transstockdet',
			'idfield'=>'transstockdetid',
			'urlsub'=>Yii::app()->createUrl('production/transstockfg/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/transstockfg/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/transstockfg/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/transstockfg/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/transstockfg/purgedetail',array('grid'=>true)),
			'ispurge'=>1,'isnew'=>0,
			'subs'=>"
				{field:'transstockdetid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'350px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',formatter: function(value,row,index){
					if (row.stockcount == '1') {
					return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
					} else {
						return formatnumber('',value);
					}
				},width:'100px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'rakasal',title:'".GetCatalog('rakasal') ."',width:'250px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'200px'},
				{field:'lotno',title:'".GetCatalog('lotno') ."',width:'150px'},
				{field:'itemnote',title:'".GetCatalog('itemnote') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'transstockid',
					title:'".GetCatalog('transstockid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'transstockdetid',
					title:'".GetCatalog('transstockdetid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'stdqty2',
					title:'".getCatalog('stdqty2') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					hidden:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'stdqty3',
					title:'".getCatalog('stdqty3') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					hidden:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'stdqty4',
					title:'".getCatalog('stdqty4') ."',
					sortable: true,
					editor: {
						type: 'numberbox',
						options: {
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					hidden:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'materialtypecode',
					title:'".GetCatalog('materialtypecode') ."',
					editor: {
						type: 'textbox',
						options:{
							readonly:true,
						}
					},
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'productid',
					title:'".GetCatalog('productname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							readonly: true,
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							onChange: function(newValue, oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'productid'});
								var stdqty2 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'stdqty4'});
								var uomid = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'uom4id'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(uom4id.target).combogrid('setValue',data.uom4);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(stdqty4.target).numberbox('setValue',data.qty4);
										$(productbomid.target).combogrid('setValue',data.bomid);
									} ,
									'cache':false});
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".GetCatalog('productid')."',width:'50px'},
								{field:'productcode',title:'".GetCatalog('productcode')."',width:'150px'},
								{field:'productname',title:'".GetCatalog('productname')."',width:'400px'},
							]]
						}	
					},
					width:'200px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productname;
					}
				},
				{
					field:'qty',
					title:'".GetCatalog('qty') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var stdqty2 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'stdqty3'});
								var stdqty4 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'stdqty4'});								
								var qty2 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'qty3'});								
								var qty4 = $('#dg-transstockfg-transstockdet').datagrid('getEditor', {index: index, field:'qty4'});						
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * newValue);
								$(qty3.target).numberbox('setValue',$(stdqty3.target).numberbox('getValue') * newValue);
								$(qty4.target).numberbox('setValue',$(stdqty4.target).numberbox('getValue') * newValue);
							}
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'uomid',
					title:'".GetCatalog('uomcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}		
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uomcode;
					}
				},
				{
					field:'qty2',
					title:'".GetCatalog('qty2') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom2id',
					title:'".GetCatalog('uom2code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom2code;
					}
				},
				{
					field:'qty3',
					title:'".GetCatalog('qty3') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom3id',
					title:'".GetCatalog('uom3code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom3code;
					}
				},
				{
					field:'qty4',
					title:'".GetCatalog('qty4') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom4id',
					title:'".GetCatalog('uom4code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							readonly:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom4code;
					}
				},
				{
					field:'storagebinfromid',
					title:'".getCatalog('storagebinfrom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'storagebinid',
							textField:'description',
							url:'".Yii::app()->createUrl('common/storagebin/indexcombosloc',array('grid'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#transstockfg-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'storagebinid',title:'".getCatalog('storagebinid')."',width:'80px'},
								{field:'description',title:'".getCatalog('description')."',width:'150px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.rakasal;
					}
				},
				{
					field:'mesinid',
					title:'".getCatalog('mesin') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'mesinid',
							textField:'namamesin',
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'mesinid',title:'".getCatalog('mesinid')."',width:'80px'},
								{field:'namamesin',title:'".getCatalog('namamesin')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.namamesin;
					}
				},
				{
					field:'lotno',
					title:'".GetCatalog('lotno')."',
					editor: {
						type: 'textbox',
					},
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'itemnote',
					title:'".GetCatalog('itemnote')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),	
));