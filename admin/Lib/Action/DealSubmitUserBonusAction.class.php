<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class DealSubmitUserBonusAction extends CommonAction{
	public function online_index()
	{	
		$now=get_gmtime();
		$map['type']=array("neq",2);
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if(intval($_REQUEST['time_status'])==1)
		{
			$map['_string'] = '(begin_time > '.get_gmtime().')';			
		}
		
		if(intval($_REQUEST['time_status'])==2)
		{
			$map['_string'] = "(begin_time < '".get_gmtime()."') and ((end_time > '".get_gmtime()."') or (end_time = 0))";
		}
		
		if(intval($_REQUEST['time_status'])==3)
		{
			$map['_string'] = '(end_time < '.get_gmtime().') and (end_time <> 0)';	
		}
		if($_REQUEST['type']=='NULL'){
			unset($_REQUEST['type']);
			
		}
		if($_REQUEST['type']!=NULL){
			$map['type']=intval($_REQUEST['type']);
		}
		
		if($_REQUEST['ips_bill_no']=='NULL'){
			unset($_REQUEST['ips_bill_no']);
		}
		if($_REQUEST['ips_bill_no']!=NULL){
			$ips_bill_no=intval($_REQUEST['ips_bill_no']);
			if($ips_bill_no>0){
				$map['_string'] = '(ips_bill_no !="")';
			}else{
				$map['_string'] = '(ips_bill_no = "")';
			}
			
		}
		
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='')
		{
			$map[DB_PREFIX.'deal.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
		
		$map['is_effect'] = 1;		
		$map['is_delete'] = 0;		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$cate_list = M("DealCate")->findAll();
		$this->assign("cate_list",$cate_list);
		$this->display ();
	}
	public function house_index()
	{
		$now=get_gmtime();
		$map['type']=2;
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if(intval($_REQUEST['time_status'])==1)
		{
			$map['_string'] = '(begin_time > '.get_gmtime().')';			
		}
		
		if(intval($_REQUEST['time_status'])==2)
		{
			$map['_string'] = "(begin_time < '".get_gmtime()."') and ((end_time > '".get_gmtime()."') or (end_time = 0))";
		}
		
		if(intval($_REQUEST['time_status'])==3)
		{
			$map['_string'] = '(end_time < '.get_gmtime().') and (end_time <> 0)';	
		}
		
		if($_REQUEST['ips_bill_no']=='NULL'){
			unset($_REQUEST['ips_bill_no']);
		}
		if($_REQUEST['ips_bill_no']!=NULL){
			$ips_bill_no=intval($_REQUEST['ips_bill_no']);
			if($ips_bill_no>0){
				$map['_string'] = '(ips_bill_no !="")';
			}else{
				$map['_string'] = '(ips_bill_no = "")';
			}
			
		}
		$cate_id=intval($_REQUEST['cate_id']);
		if($cate_id>0)
		{
			$cate = M("DealHouseCate")->where('id='.$cate_id)->find();
			if($cate['pid'] ==0)
			{
				$cate_ids = $GLOBALS['db']->getOne("select Group_concat(id) from ".DB_PREFIX."deal_house_cate where pid=".$cate_id." and is_effect=1");
				if($cate_ids)
				{
					$cate_array=explode(',',$cate_ids);
					$cate_array[]=$cate_id;
				}else
				{
					$cate_array[]=$cate_id;
				}
				$map['cate_id'] = array('in',$cate_array);
			}
			else
			{
				$map['cate_id'] = intval($_REQUEST['cate_id']);	
			}
		}
		
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='')
		{
			$map[DB_PREFIX.'deal.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
		
		$map['is_effect'] = 1;		
		$map['is_delete'] = 0;		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$cate_list = M("DealHouseCate")->where('is_effect = 1')->findAll();
		$cate_list = D("DealHouseCate")->toNameFormatTree($cate_list);
		$this->assign("cate_list",$cate_list);
		
		$this->assign("type",2);
		$this->display ();
	}
	public function submit_index()
	{
		$map['type']=array('neq',2);
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='')
		{
			$map[DB_PREFIX.'deal.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
		
		if($_REQUEST['type']=='NULL'){
			unset($_REQUEST['type']);
		}
		
		if($_REQUEST['type']!=NULL){
 			$map['type']=intval($_REQUEST['type']);
		}
		if($_REQUEST['ips_bill_no']=='NULL'){
			unset($_REQUEST['ips_bill_no']);
		}
		if($_REQUEST['ips_bill_no']!=NULL){
			$ips_bill_no=intval($_REQUEST['ips_bill_no']);
			if($ips_bill_no>0){
				$map['_string'] = '(ips_bill_no !="")';
			}else{
				$map['_string'] = '(ips_bill_no = "")';
			}
			
		}
		$map['is_effect'] = array("in",array(0,2));
		$map['is_delete'] = 0;		

		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$cate_list = M("DealCate")->findAll();
		$this->assign("cate_list",$cate_list);

		$this->display ();
	}
	
	public function submit_house_index()
	{
		$map['type']=array('eq',2);
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		$create_time_2=empty($_REQUEST['create_time_2'])?to_date($now,'Y-m-d'):strim($_REQUEST['create_time_2']);
		$create_time_2=to_timespan($create_time_2)+24*3600;
		if(trim($_REQUEST['create_time_1'])!='')
		{
			$map[DB_PREFIX.'deal.create_time'] = array('between',array(to_timespan($_REQUEST['create_time_1']),$create_time_2));
		}
		
		if($_REQUEST['ips_bill_no']=='NULL'){
			unset($_REQUEST['ips_bill_no']);
		}
		if($_REQUEST['ips_bill_no']!=NULL){
			$ips_bill_no=intval($_REQUEST['ips_bill_no']);
			if($ips_bill_no>0){
				$map['_string'] = '(ips_bill_no !="")';
			}else{
				$map['_string'] = '(ips_bill_no = "")';
			}
			
		}
		$map['is_effect'] = array("in",array(0,2));
		$map['is_delete'] = 0;		

		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$cate_list = M("DealHouseCate")->findAll();
		$this->assign("cate_list",$cate_list);
		$this->assign("type",2);
		$this->display ("submit_index");
	}
	
	public function delete_index()
	{
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		

		$map['is_delete'] = 1;
		$map['type'] = array('neq',2);		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$cate_list = M("DealCate")->findAll();
		$this->assign("cate_list",$cate_list);
		
		$this->assign("action_name",'delete_index');
		
		$this->display ();
	}
	
	public function delete_house_index(){
		if(trim($_REQUEST['name'])!='')
		{
			$map['name'] = array('like','%'.trim($_REQUEST['name']).'%');
		}
		
		if(intval($_REQUEST['cate_id'])>0)
		{
			$map['cate_id'] = intval($_REQUEST['cate_id']);
		}
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		if(intval($_REQUEST['user_id'])>0)
		{
			$map['user_id'] = intval($_REQUEST['user_id']);
		}
		

		$map['is_delete'] = 1;
		$map['type'] = 2;		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		
		$cate_list = M("DealHouseCate")->findAll();
		$this->assign("cate_list",$cate_list);
		
		$this->assign("action_name",'delete_house_index');
		
		$this->display ("delete_index");
	}
	
	public function add()
	{
		$type = intval($_REQUEST['type']);
		$this->assign("type",$type);
		
		if($type ==2)
		{
			$cate_list = M("DealHouseCate")->findAll();
			$cate_list = D("DealHouseCate")->toNameFormatTree($cate_list);

			$this->assign("houses_status_list",get_houses_status_list());
		}else
		{
			$cate_list = M("DealCate")->findAll();
			$cate_list = D("DealCate")->toNameFormatTree($cate_list);
		}
		$this->assign("cate_list",$cate_list);
		
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$this->assign("region_lv2",$region_lv2);
		//项目等级
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$this->assign("new_sort", M("Deal")->max("sort")+1);
		$this->display();
	}
	
	public function add_investor(){
		$cate_list = M("DealCate")->findAll();
		$cate_list = D("DealCate")->toNameFormatTree($cate_list);
		$this->assign("cate_list",$cate_list);
		
		$type = intval($_REQUEST ['type']);
		$this->assign("type",$type);
		if($type==4){
			//最大融资轮数
			$invest_phase='';
			$project_invest_phase='';//通过审核项目 的最大融资轮数
			$company_investment_case='';//融资经历最大融资轮数
			$project_invest_phase=$GLOBALS['db']->getOne("select max(invest_phase) from ".DB_PREFIX."deal where user_id = ".$vo['user_id']." and company_id =".$vo['company_id']." and is_effect=1 and is_delete=0 and type = 4");
			$company_investment_case=$GLOBALS['db']->getOne("select max(invest_phase) from ".DB_PREFIX."finance_company_investment_case where  company_id =".$vo['company_id']." and status =1");	
			$invest_phase=$project_invest_phase>$company_investment_case?$project_invest_phase:$company_investment_case;
			$this->assign("invest_phase",$invest_phase);
		}
	
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		$this->assign("region_lv2",$region_lv2);
		//项目等级
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
  		$this->assign("new_sort", M("Deal")->max("sort")+1);
 		
 		$this->assign('stock_num',0);
 		$this->assign('unstock_num',0);
		//
		$this->assign("history_num",0);
		$plan_html=$this->fetch("add_new_history");
		$this->assign('history_html',$plan_html);
		
		$this->assign('plan_num',0);
		$this->assign('plan_step_num',0);
		$plan_html=$this->fetch("add_new_plan");
		$this->assign('plan_html',$plan_html);
		
		$this->assign("action",'insert_investor');
		$this->assign('attach_num',1);
		$attach_html=$this->fetch("add_new_attachment");
		$this->assign('attach_html',$attach_html);
		
		$this->display("edit_investor");
	}
	public function add_investor_item(){
		$return=array('status'=>1,'html'=>'');
		$num=intval($_REQUEST['num']);
		$html=strim($_REQUEST['html']);
		if($html=='add_new_plan'){
			$this->assign('plan_num',$num);
		}elseif($html='add_new_history'){
			$this->assign('history_num',$num);
		}
		$this->assign('num',$num);
		$return['html']=$this->fetch($html);
		ajax_return($return);
	}
	public function edit() {
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M("Deal")->where($condition)->find();
		if($vo['user_id']==0)$vo['user_id']  = '';
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
 		$this->assign ( 'vo', $vo );
		$this->assign ( 'aa', 10 );
		if($vo['type'] ==2)
		{
			$cate_list = M("DealHouseCate")->findAll();
			$cate_list = D("DealHouseCate")->toNameFormatTree($cate_list);
			
			$this->assign("houses_status_list",get_houses_status_list());
		}
		else
		{
			$cate_list = M("DealCate")->findAll();
			$cate_list = D("DealCate")->toNameFormatTree($cate_list);
		}
		
		$this->assign("cate_list",$cate_list);
		
		$region_pid = 0;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		foreach($region_lv2 as $k=>$v)
		{
			if($v['name'] == $vo['province'])
			{
				$region_lv2[$k]['selected'] = 1;
				$region_pid = $region_lv2[$k]['id'];
				break;
			}
		}
		$this->assign("region_lv2",$region_lv2);
		
		
		if($region_pid>0)
		{
			$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
			foreach($region_lv3 as $k=>$v)
			{
				if($v['name'] == $vo['city'])
				{
					$region_lv3[$k]['selected'] = 1;
					break;
				}
			}
			$this->assign("region_lv3",$region_lv3);
		}
		
		$qa_list = M("DealFaq")->where("deal_id=".$vo['id'])->order("sort asc")->findAll();
		$this->assign("faq_list",$qa_list);
		
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		//$deal_imgs
		$deal_imges_list=D("dealImage")->where("deal_id=".$id."")->findAll();
		$this->assign("deal_imgs",array_map('array_pop',$deal_imges_list));
		
		$this->display ();
	}
	
	public function edit_investor() {		
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M("Deal")->where($condition)->find();
		$type = $vo['type'];
		$this->assign("type",$type);
		if($vo['type'] == 4){
			//最大融资轮数
			$invest_phase='';
			$project_invest_phase='';//通过审核项目 的最大融资轮数
			$company_investment_case='';//融资经历最大融资轮数
			$project_invest_phase=$GLOBALS['db']->getOne("select max(invest_phase) from ".DB_PREFIX."deal where user_id = ".$vo['user_id']." and company_id =".$vo['company_id']." and is_effect=1 and is_delete=0 and type = 4");
			$company_investment_case=$GLOBALS['db']->getOne("select max(invest_phase) from ".DB_PREFIX."finance_company_investment_case where  company_id =".$vo['company_id']." and status =1");	
			$invest_phase=$project_invest_phase>$company_investment_case?$project_invest_phase:$company_investment_case;
			$this->assign("invest_phase",$invest_phase);
		}
	
		if($vo['user_id']==0)$vo['user_id']  = '';
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
		$vo['pay_end_time'] = $vo['pay_end_time']!=0?to_date($vo['pay_end_time']):'';
		$vo['business_create_time'] = $vo['business_create_time']!=0?to_date($vo['business_create_time']):'';
		$vo['history']=unserialize($vo['history']);
 		$history_num=$vo['history']?count($vo['history']):0;
  		$this->assign('history_num',$history_num);
		$vo['plan']=unserialize($vo['plan']);
 		$plan_num=$vo['plan']?count($vo['plan']):0;
   		$this->assign('plan_step_num',$plan_num);
		$vo['attach']=unserialize($vo['attach']);
  		$attach_num=$vo['attach']?count($vo['attach']):0;
		$this->assign('attach_num',$attach_num);
		$vo['stock']=unserialize($vo['stock']);
 		$stock_num=$vo['stock']?count($vo['stock']):0;
 		$this->assign('stock_num',$stock_num);

		$vo['unstock']=unserialize($vo['unstock']);
		$unstock_num=$vo['unstock']?count($vo['unstock']):0;
 		$this->assign('unstock_num',$unstock_num);
 		
 		//企业资质材料信息
 		$vo['audit_data']=unserialize($vo['audit_data']);
 		$audit_data=$vo['audit_data'];
 		$this->assign('audit_data',$audit_data);
 		
		$this->assign ( 'vo', $vo );
		$this->assign("action",'update_investor');
		$plan_html=$this->fetch("add_new_history");
 		$this->assign('history_html',$plan_html);
		
 		$this->assign('plan_num',1);
		$plan_html=$this->fetch("add_new_plan");
		$this->assign('plan_html',$plan_html);
		
		$user_level = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."user_level order by level ASC");
		$this->assign("user_level",$user_level);
		
		$cate_list = M("DealCate")->findAll();
		$cate_list = D("DealCate")->toNameFormatTree($cate_list);
		$this->assign("cate_list",$cate_list);
		
		$region_pid = 0;
		$region_lv2 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where region_level = 2 order by py asc");  //二级地址
		foreach($region_lv2 as $k=>$v)
		{
			if($v['name'] == $vo['province'])
			{
				$region_lv2[$k]['selected'] = 1;
				$region_pid = $region_lv2[$k]['id'];
				break;
			}
		}
		$this->assign("region_lv2",$region_lv2);
		
		
		if($region_pid>0)
		{
			$region_lv3 = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."region_conf where pid = ".$region_pid." order by py asc");  //三级地址
			foreach($region_lv3 as $k=>$v)
			{
				if($v['name'] == $vo['city'])
				{
					$region_lv3[$k]['selected'] = 1;
					break;
				}
			}
			$this->assign("region_lv3",$region_lv3);
		}
		
		$qa_list = M("DealFaq")->where("deal_id=".$vo['id'])->order("sort asc")->findAll();
		$this->assign("faq_list",$qa_list);
		
		//合同
		$contract_list = M("Contract")->findAll();
		$contract_list = D("Contract")->toFormatTree($contract_list);
		$this->assign("contract_list",$contract_list);
 		//$deal_imgs
		$deal_imges_list=D("dealImage")->where("deal_id=".$id."")->findAll();
		$this->assign("deal_imgs",array_map('array_pop',$deal_imges_list));
		
		$this->display ();
	}
	
	
	public function insert() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M("Deal")->create ();
		
		$data['type']=intval($data['type']);
		//开始验证有效性
		$this->assign("jumpUrl",u("Deal"."/add?type=".$data['type']));
		if(!check_empty($data['name']))
		{
			$this->error("请输入名称");
		}	
		
		if(intval($data['cate_id'])==0)
		{
			$this->error("请选择分类");
		}	
		if(floatval($data['limit_price'])<=0){
			$this->error("目标金额要大于0");
		}
		if($data['type']==2)
		{
			$publisher_user=M("User")->where("id=".intval($data['user_id'])."")->find();
			if($publisher_user['is_investor'] !=2 || $publisher_user['investor_status'] !=1 )
				$this->error("房产众筹发启人要是企业型会员");
		}	
		$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
		if($data['begin_time']>$data['end_time']){
			$this->error("开始时间不能大于结束 时间");
		}
 		$data['create_time'] = get_gmtime();
		$data['user_name'] = M("User")->where("id=".intval($data['user_id']))->getField("user_name");
		if(!$data['user_name'] )$data['user_name'] ="";
		if($data['vedio']!="")
		{
			$data['source_vedio'] = $data['vedio'];
		}
		
		// 更新数据
		$log_info = $data['name'];
		
		if(!$data['ips_bill_no'])
		{
			$data['ips_bill_no']='';
		}
		
		
		$list=M("Deal")->add($data);

		if (false !== $list) {
			//成功提示
			
			if($data['is_effect']==1&&$data['user_id']>0)
			{
				$deal_count = M("Deal")->where("user_id=".$data['user_id']." and is_effect = 1 and is_delete = 0")->count();
				M("User")->where("id=".$data['user_id'])->setField("build_count",$deal_count);
			}
			
			foreach($_REQUEST['question'] as $k=>$v)
			{
				if(trim($v)!=""||trim($_REQUEST['answer'][$k])!='')
				{
					$qa = array();
					$qa['deal_id'] = $list;
					$qa['question'] = trim($v);
					$qa['answer'] = trim($_REQUEST['answer'][$k]);
					$qa['sort'] = intval($k)+1;
					M("DealFaq")->add($qa);
				}
			}
			if($_REQUEST['ips_bill_no']>0){
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set ips_bill_no=$list where id=".$list);
			}
			//插入图片
			$this->images_add($list);
			
			syn_deal($list);
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}	
	public function insert_investor(){
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
 		$data = M("Deal")->create ();
 		//开始验证有效性
		$this->assign("jumpUrl",u("Deal"."/add_investor"));
		if(!check_empty($data['name']))
		{
			$this->error("请输入名称");
		}	
		
		if(intval($data['cate_id'])==0)
		{
			$this->error("请选择分类");
		}	
		if(floatval($data['limit_price'])<=0){
			$this->error("目标金额要大于0");
		}
		$history_info=deal_investor_info($data['history'],'history');
   		if($history_info['status']){
			$data['history']=serialize(array_filter($history_info['data']));
		}else{
			$this->error($history_info['info']);
		}
		if($data['stock']){
			$stock_info=deal_investor_info($data['stock'],'stock');
			if($stock_info['status']){
				$data['stock']=serialize(array_filter($stock_info['data']));
			}else{
	 			$this->error($stock_info['info']);
			}
		}
	 		
 		$unstock_info=deal_investor_info($data['unstock'],'unstock');
		if($unstock_info['status']){
			$data['unstock']=serialize(array_filter($unstock_info['data']));
		}else{
			$this->error($unstock_info['info']);
		}
 		$plan_info=deal_investor_info($data['plan'],'plan');
		if($plan_info['status']){
			$data['plan']=serialize(array_filter($plan_info['data']));
		}else{
			$this->error($plan_info['info']);
		}
   		$attach_info=deal_investor_info($data['attach'],'attach');
 		if($attach_info['status']){
			$data['attach']=serialize(array_filter($attach_info['data']));
		}else{
			$this->error($attach_info['info']);
		}
		
		$data['audit_data']=serialize($data['audit_data']);
		if($data['end_time']>$data['pay_end_time']){
			$this->error("支付结束时间要大于项目结束时间");
		}elseif($data['begin_time']>$data['end_time']){
			$this->error("项目结束时间要大于项目开始时间");
		}
		
 		$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
		$data['pay_end_time'] = trim($data['pay_end_time'])==''?0:to_timespan($data['pay_end_time']);
		$data['business_create_time'] = trim($data['business_create_time'])==''?0:to_timespan($data['business_create_time']);
		
		$data['create_time'] = get_gmtime();
		$data['user_name'] = M("User")->where("id=".intval($data['user_id']))->getField("user_name");
		if(!$data['user_name'] )$data['user_name'] ="";
		if($data['vedio']!="")
		{
			$data['source_vedio'] = $data['vedio'];
		}
		
		// 更新数据
		$log_info = $data['name'];
		
		$list=M("Deal")->add($data);

		if (false !== $list) {
			//成功提示
			
			if($data['is_effect']==1&&$data['user_id']>0)
			{
				$deal_count = M("Deal")->where("user_id=".$data['user_id']." and is_effect = 1 and is_delete = 0")->count();
				M("User")->where("id=".$data['user_id'])->setField("build_count",$deal_count);
			}
			
			foreach($_REQUEST['question'] as $k=>$v)
			{
				if(trim($v)!=""||trim($_REQUEST['answer'][$k])!='')
				{
					$qa = array();
					$qa['deal_id'] = $list;
					$qa['question'] = trim($v);
					$qa['answer'] = trim($_REQUEST['answer'][$k]);
					$qa['sort'] = intval($k)+1;
					M("DealFaq")->add($qa);
				}
			}
			if($_REQUEST['ips_bill_no']>0){
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set ips_bill_no=$list where id=".$list);
			}
			
			//插入图片
			$this->images_add($list);
			
			syn_deal($list);
			save_log($log_info.L("INSERT_SUCCESS"),1);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}

	public function update() {
		B('FilterString');
		$data = M("Deal")->create();
		$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$data['id']);
		$this->assign("jumpUrl",u("Deal"."/edit",array("id"=>$data['id'])));
		
 			$log_info = M("Deal")->where("id=".intval($data['id']))->getField("name");
			
			$this->deal_update(intval($data['id']));
	 		//开始验证有效性
			
			if(!check_empty($data['name']))
			{
				$this->error("请输入名称");
			}	
			if(intval($data['cate_id'])==0)
			{
				$this->error("请选择分类");
			}
			if(floatval($data['limit_price'])<=0){
				$this->error("目标金额要大于0");
			}
			if(intval($deal_info['type'])==2)
			{
				$publisher_user=M("User")->where("id=".intval($data['user_id'])."")->find();
				if($publisher_user['is_investor'] !=2 || $publisher_user['investor_status'] !=1 )
					$this->error("房产众筹发启人要是企业型会员");
			}	
			$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
			$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
			if($data['begin_time']>$data['end_time']){
				$this->error("开始时间不能大于结束 时间");
			}
			
			$data['user_name'] = M("User")->where("id=".intval($data['user_id']))->getField("user_name");
			if(!$data['user_name'] )$data['user_name'] ="";
			if($data['vedio']!="")
			{
				$data['source_vedio'] = $data['vedio'];
			}
			else
			{
				$data['source_vedio'] = "";
			}
		if($_REQUEST['ips_bill_no']>0){
			$data['ips_bill_no'] = intval($data['id']);
 		}else{
 			$data['ips_bill_no'] = '';
 		}
 		if($data['is_effect']==2&&$data['user_id']>0)
		{
			$data['is_edit'] = 1;
		}
		$list=M("Deal")->save ($data);
		if (false !== $list) {
			if($deal_info['is_effect']!=$data['is_effect']){
				if($data['is_effect']==1){
					$GLOBALS['msg']->manage_msg($GLOBALS['msg']::MSG_ZC_STATUS,$deal_info['user_id'],array('deal_id'=>$deal_info['id'],'deal_status'=>$GLOBALS['msg']::CROW_EXAMINE_SUCCESS));
				}elseif($data['is_effect']==2){
					$GLOBALS['msg']->manage_msg($GLOBALS['msg']::MSG_ZC_STATUS,$deal_info['user_id'],array('deal_id'=>$deal_info['id'],'deal_status'=>$GLOBALS['msg']::CROW_EXAMINE_FAIL));
				}
			}
			if($data['is_effect']==1&&$data['user_id']>0)
			{
				$deal_count = M("Deal")->where("user_id=".$data['user_id']." and is_effect = 1 and is_delete = 0")->count();
				M("User")->where("id=".$data['user_id'])->setField("build_count",$deal_count);
			}
			
			//插入图片
			D("dealImage")->where("deal_id = ".intval($data['id'])."")->delete();
			$this->images_add($data['id']);
			
			//成功提示			
			M("DealFaq")->where("deal_id=".$data['id'])->delete();
			foreach($_REQUEST['question'] as $k=>$v)
			{
				if(trim($v)!=""||trim($_REQUEST['answer'][$k])!='')
				{
					$qa = array();
					$qa['deal_id'] = $data['id'];
					$qa['question'] = trim($v);
					$qa['answer'] = trim($_REQUEST['answer'][$k]);
					$qa['sort'] = intval($k)+1;
					M("DealFaq")->add($qa);
				}
			}
			M("Deal")->where("id=".$data['id'])->setField("deal_extra_cache","");
			M("DealLog")->where("deal_id=".$data['id'])->setField("deal_info_cache","");
			M("DealComment")->where("deal_id=".$data['id'])->setField("deal_info_cache","");
			syn_deal($data['id']);
			syn_deal_status($data['id']);
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	public function update_all(){
		$re=$GLOBALS['db']->getAll("select * from  ".DB_PREFIX."deal where  is_effect = 1 and is_delete=0 ");
		foreach($re as $k=>$v){
			syn_deal($v['id']);
			syn_deal_status($v['id']);
		}
		syn_user_level();
		ajax_return(array('status'=>1));
	}
	public function update_investor() {
		$now_time=get_gmtime();
		B('FilterString');
 		$data = M("Deal")->create();
			$log_info = M("Deal")->where("id=".intval($data['id']))->getField("name");
			$deal_info = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id=".$data['id']);
			//开始验证有效性
			$this->assign("jumpUrl",u("Deal"."/edit_investor",array("id"=>$data['id'])));
			if($deal_info['type'] ==4){
				$deal_count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal where is_effect = 1 and is_delete = 0 and ( (end_time>".$now_time.") or (pay_end_time>".$now_time." and is_success=1)) and id!=".$data['id']." and company_id =".$deal_info['company_id']);
				if($data['is_effect']==1){
					if($deal_count)
					{
						$this->error("审核无法通过，一个公司只能有一个进行中融资项目！");
					}
				}
				
			}
			if(!check_empty($data['name']))
			{
				$this->error("请输入名称");
			}	
			if(intval($data['cate_id'])==0)
			{
				$this->error("请选择分类");
			}
			if(floatval($data['limit_price'])<=0){
				$this->error("目标金额要大于0");
			}
			$this->deal_update(intval($data['id']));
			
	    	$history_info=deal_investor_info($data['history'],'history');
	   		if($history_info['status']){
				$data['history']=serialize(array_filter($history_info['data']));
			}else{
				$this->error($history_info['info']);
			}
	  		$stock_info=deal_investor_info($data['stock'],'stock');
			if($stock_info['status']){
				$data['stock']=serialize(array_filter($stock_info['data']));
			}else{
	 			$this->error($stock_info['info']);
			}
	 		$unstock_info=deal_investor_info($data['unstock'],'unstock');
			if($unstock_info['status']){
				$data['unstock']=serialize(array_filter($unstock_info['data']));
			}else{
				$this->error($unstock_info['info']);
			}
	 		$plan_info=deal_investor_info($data['plan'],'plan');
			if($plan_info['status']){
				$data['plan']=serialize(array_filter($plan_info['data']));
			}else{
				$this->error($plan_info['info']);
			}
	   		$attach_info=deal_investor_info($data['attach'],'attach');
	 		if($attach_info['status']){
				$data['attach']=serialize(array_filter($attach_info['data']));
			}else{
				$this->error($attach_info['info']);
			}
			//企业资质材料信息
			$data['audit_data']=serialize($data['audit_data']);
			if($data['end_time']>$data['pay_end_time']){
				$this->error("支付结束时间要大于项目结束时间");
			}elseif($data['begin_time']>$data['end_time']){
				$this->error("项目结束时间要大于项目开始时间");
			}
			
			
			$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
			$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
			$data['pay_end_time'] = trim($data['pay_end_time'])==''?0:to_timespan($data['pay_end_time']);
			
			$data['business_create_time'] = trim($data['business_create_time'])==''?0:to_timespan($data['business_create_time']);
		
			$data['user_name'] = M("User")->where("id=".intval($data['user_id']))->getField("user_name");
			if(!$data['user_name'] )$data['user_name'] ="";
			if($data['vedio']!="")
			{
				$data['source_vedio'] = $data['vedio'];
			}
			else
			{
				$data['source_vedio'] = "";
			}
	 	if($_REQUEST['ips_bill_no']>0){
			$data['ips_bill_no'] = intval($data['id']);
 		}else{
 			$data['ips_bill_no'] = '';
 		}
 		if($data['is_effect']==2)
 		{
 			$data['is_edit'] =1;
 		}
		$list=M("Deal")->save ($data);
		if (false !== $list) {
			if($deal_info['is_effect']!=$data['is_effect']){
				if($data['is_effect']==1){
					$GLOBALS['msg']->manage_msg($GLOBALS['msg']::MSG_ZC_STATUS,$deal_info['user_id'],array('deal_id'=>$deal_info['id'],'deal_status'=>$GLOBALS['msg']::CROW_EXAMINE_SUCCESS));
				}elseif($data['is_effect']==2){
					$GLOBALS['msg']->manage_msg($GLOBALS['msg']::MSG_ZC_STATUS,$deal_info['user_id'],array('deal_id'=>$deal_info['id'],'deal_status'=>$GLOBALS['msg']::CROW_EXAMINE_FAIL));
				}
			}
			if($data['is_effect']==1&&$data['user_id']>0)
			{
				$deal_count = M("Deal")->where("user_id=".$data['user_id']." and is_effect = 1 and is_delete = 0")->count();
				M("User")->where("id=".$data['user_id'])->setField("build_count",$deal_count);
			}
			//成功提示			
			M("DealFaq")->where("deal_id=".$data['id'])->delete();
			foreach($_REQUEST['question'] as $k=>$v)
			{
				if(trim($v)!=""||trim($_REQUEST['answer'][$k])!='')
				{
					$qa = array();
					$qa['deal_id'] = $data['id'];
					$qa['question'] = trim($v);
					$qa['answer'] = trim($_REQUEST['answer'][$k]);
					$qa['sort'] = intval($k)+1;
					M("DealFaq")->add($qa);
				}
			}
			
			//插入图片
			D("dealImage")->where("deal_id = ".intval($data['id'])."")->delete();
			$this->images_add($data['id']);
			
			M("Deal")->where("id=".$data['id'])->setField("deal_extra_cache","");
			M("DealLog")->where("deal_id=".$data['id'])->setField("deal_info_cache","");
			M("DealComment")->where("deal_id=".$data['id'])->setField("deal_info_cache","");
			//syn_deal($data['id']);
			//syn_deal_status($data['id']);
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"),0,$log_info.L("UPDATE_FAILED"));
		}
	}
	public function set_sort()
	{
		$id = intval($_REQUEST['id']);
		$sort = intval($_REQUEST['sort']);
		$log_info = M("Deal")->where("id=".$id)->getField("name");
		if(!check_sort($sort))
		{
			$this->error(l("SORT_FAILED"),1);
		}
		M("Deal")->where("id=".$id)->setField("sort",$sort);
		save_log($log_info.l("SORT_SUCCESS"),1);
		$this->success(l("SORT_SUCCESS"),1);
	}
	
	public function delete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("Deal")->where($condition)->findAll();
				$num = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$id." and order_status=3 and is_refund=0");			
				if(intval($num)>0){
					$this->error ("该项目已经有人成功付款,无法删除",$ajax);
				}
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];
				}
				if($info) $info = implode(",",$info);
				$list = M("Deal")->where ( $condition )->setField("is_delete",1);		
						
				if ($list!==false) {
					foreach($rel_data as $data)
					{						
						$deal_count = M("Deal")->where("user_id=".$data['user_id']." and is_effect = 1 and is_delete = 0")->count();
						M("User")->where("id=".$data['user_id'])->setField("build_count",$deal_count);						
					}
					save_log($info."成功移到回收站",1);
					$this->success ("成功移到回收站",$ajax);
				} else {
					save_log($info."移到回收站出错",0);					
					$this->error ("移到回收站出错",$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function restore() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("Deal")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("Deal")->where ( $condition )->setField("is_delete",0);				
				if ($list!==false) {
					save_log($info."恢复成功",1);
					$this->success ("恢复成功",$ajax);
				} else {
					save_log($info."恢复出错",0);
					$this->error ("恢复出错",$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function foreverdelete() {
		//彻底删除指定记录
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
				$link_condition = array ('deal_id' => array ('in', explode ( ',', $id ) ) );
				$rel_data = M("Deal")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['name'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("Deal")->where ( $condition )->delete();				
				if ($list!==false) {					
					M("DealFaq")->where($link_condition)->delete();
					M("DealComment")->where($link_condition)->delete();
					M("DealFocusLog")->where($link_condition)->delete();
					M("DealImage")->where($link_condition)->delete();
					M("DealItem")->where($link_condition)->delete();
					M("DealItemImage")->where($link_condition)->delete();
					M("DealOrder")->where($link_condition)->delete();
					M("DealOrderLottery")->where($link_condition)->delete();
					M("DealPayLog")->where($link_condition)->delete();
					M("DealSupportLog")->where($link_condition)->delete();
					M("DealVisitLog")->where($link_condition)->delete();
					M("DealLog")->where($link_condition)->delete();
					M("UserDealNotify")->where($link_condition)->delete();
					M("DealNotify")->where($link_condition)->delete();
					M("InvestmentList")->where($link_condition)->delete();
					
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function add_faq()
	{
		$this->display();
	}
	
	public function deal_item()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		$this->assign("deal_info",$deal_info);
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];		
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
			$name=$this->getActionName();
			$model = D ("DealItem");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
		}
		
		$this->display();
	}
	
	public function add_deal_item()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		$Count = M('DealItem')->where('deal_id = '.$deal_id)->count();
		$this->assign("deal_info",$deal_info);
		$this->display();
	}
	
	
	public function insert_deal_item() {
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M("DealItem")->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u("Deal"."/add_deal_item",array("id"=>$data['deal_id'])));
		if(!check_empty($data['price'])&&$data['type']==0)
		{
			$this->error("请输入价格");
		}
		if($data['type'] ==2 && $data['lottery_measure'] <=0)
		{
			$this->error("请输入抽奖计量数");
		}
		if( $data['is_limit_user']==1 && $data['virtual_person'] > $data['limit_user'])
				$this->error("虚拟购买人数不能大于限购人数");	
				
		$deal=M("Deal")->where("id=".$data['deal_id'])->find();
		if($deal['lottery_draw_time'] >0 && $data['type'] ==2)
		{
			$this->error("项目幸运号已揭晓，不能增加抽奖子项目");
		}
		
		// 更新数据
		$list=M("DealItem")->add($data);
		$log_info =  "项目ID".$data['deal_id'].":".format_price($data['price']);	
		
		if (false !== $list) {
			//成功提示
			
			M("DealItemImage")->where("deal_item_id=".$data['id'])->delete();
			$imgs=array($_REQUEST['img0'],$_REQUEST['img1'],$_REQUEST['img2'],$_REQUEST['img3']);
			
			//$imgs = $_REQUEST['image'];
			foreach($imgs as $k=>$v)
			{
				if($v!='')
				{
					$img_data['deal_id'] = $data['deal_id'];
					$img_data['deal_item_id'] = $list;
					$img_data['image'] = $v;
					M("DealItemImage")->add($img_data);
				}
			}
			M("Deal")->where("id=".$data['deal_id'])->setField("deal_extra_cache","");
			save_log($log_info.L("INSERT_SUCCESS"),1);
			syn_deal($data['deal_id']);
			syn_deal_status($data['deal_id']);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
	}
	
	public function edit_deal_item()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M("DealItem")->where($condition)->find();
		$this->assign ( 'vo', $vo );
		
		//输出图片集
		$img_list = M("DealItemImage")->where("deal_item_id=".$vo['id'])->findAll();
		$imgs = array();
		foreach($img_list as $k=>$v)
		{
			$imgs[$k] = $v['image']; 
		}
		$this->assign("img_list",$imgs);
		
		$deal_info = M("Deal")->where("id=".intval($vo['deal_id'])."")->find();
		$this->assign ( 'deal_info', $deal_info );
		
		$this->display();
	}
	
	public function update_deal_item() {
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M("DealItem")->create ();
		
		//开始验证有效性
		$this->assign("jumpUrl",u("Deal"."/edit_deal_item",array("id"=>$data['id'])));
		
		$deal_item=M("DealItem")->getById(intval($data['id']));
		
		if(!$deal_item)
			$this->error("更新失败");
		
		if($deal_item['type'] ==2 && $deal_item['lottery_draw_time'] >0)
		{
			$this->error("子分类已抽奖，不能再编辑");
		}
			
		if(!check_empty($data['price']))
		{
			$this->error("请输入价格");
		}
		
		if($data['type'] ==2)
		{
			if($data['lottery_measure'] <=0)
			{
				$this->error("请输入抽奖计量数");
			}
			
			$deal=M("Deal")->where("id=".$data['deal_id'])->find();
			if($deal['lottery_draw_time'] >0)
			{
				$this->error("项目幸运号已揭晓，不能增加抽奖子项目");
			}
			
		}
		
		$data['virtual_person']=intval($data['virtual_person']);
		$data['limit_user']=intval($data['limit_user']);
		if( $data['type'] == 0 && $data['is_limit_user']==1 && $data['virtual_person'] > $data['limit_user'])
				$this->error("虚拟购买人数不能大于限购人数".$data['limit_user']);
		
		if( $data['is_limit_user']==1 && $deal_item['support_count'] >0 && ($data['virtual_person']+$deal_item['support_count']) > $data['limit_user'])
		{
			if($data['virtual_person'] >0)
				$err_info='限购人数小于"虚拟购买人数('.$data['virtual_person'].')+支持人数('.$deal_item['support_count'].')"';
			else
				$err_info='限购人数小于"支持人数('.$deal_item['support_count'].')"';
			$this->error($err_info);
		}		
		
		
	
		// 更新数据
		$this->deal_update(intval($data['deal_id']));
		$list=M("DealItem")->save($data);
		
		$log_info =  "项目ID".$data['deal_id'].":".format_price($data['price']);	
		if (false !== $list) {
			if($data['virtual_person']>0){
				
			}
			//成功提示
			//开始处理图片
			M("DealItemImage")->where("deal_item_id=".$data['id'])->delete();
			$imgs=array($_REQUEST['img0'],$_REQUEST['img1'],$_REQUEST['img2'],$_REQUEST['img3']);
			//$imgs = $_REQUEST['image'];
			foreach($imgs as $k=>$v)
			{
				if($v!='')
				{
					$img_data['deal_item_id'] = $data['id'];
					$img_data['deal_id'] = $data['deal_id'];
					$img_data['image'] = $v;
					M("DealItemImage")->add($img_data);
				}
			}
			M("Deal")->where("id=".$data['deal_id'])->setField("deal_extra_cache","");
			M("DealLog")->where("deal_id=".$data['deal_id'])->setField("deal_info_cache","");
			//end 处理图片
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			syn_deal($data['deal_id']);
			syn_deal_status($data['deal_id']);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"));
		}
	}
	
	public function del_deal_item()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("DealItem")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = format_price($data['price']);	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id.":".$info;
				$list = M("DealItem")->where ( $condition )->delete();				
				if ($list!==false) {					
					M("Deal")->where("id=".$deal_id)->setField("deal_extra_cache","");
					syn_deal($deal_id);
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	
	
	//pay_log 放款日志
	public function pay_log()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		
		//拥金
		$deal_info['commission'] = $deal_info['support_amount'] + $deal_info['delivery_fee_amount'] - ($deal_info['pay_amount']+$deal_info['share_fee_amount']) ;
		$this->assign("deal_info",$deal_info);
		
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];	

			$model = D ("DealPayLog");
			$paid_money = $model->where($map)->sum("money");
			$remain_money = $deal_info['pay_amount'] - $paid_money;
			$this->assign("remain_money",$remain_money);
			$this->assign("paid_money",$paid_money);
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
			
			//分红情况
			$share_fee_total =  D ("DealOrder")->where("deal_id=".$deal_id."")->sum("share_fee");
			$share_fee_issue =  D ("DealOrder")->where("deal_id=".$deal_id." and share_status=1 ")->sum("share_fee");
			$this->assign("share_fee_total",$share_fee_total);
			$this->assign("share_fee_issue",$share_fee_issue);
		}
		$this->display();
	}
	
	public function add_pay_log()
	{
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		
		//拥金
		$deal_info['commission'] = $deal_info['support_amount'] + $deal_info['delivery_fee_amount'] - ($deal_info['pay_amount']+$deal_info['share_fee_amount']) ;
		
		$this->assign("deal_info",$deal_info);
		
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];		
	
			$model = D ("DealPayLog");
			$paid_money = $model->where($map)->sum("money");
			$remain_money = $deal_info['pay_amount'] - $paid_money;
			$this->assign("paid_money",$paid_money);
			$this->assign("remain_money",$remain_money);
		}
		
		$this->display();
	}
	
	public function save_pay_log()
	{
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
	
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];		
		
			$model = D ("DealPayLog");
			$paid_money = $model->where($map)->sum("money");
			$remain_money = $deal_info['pay_amount'] - $paid_money;
			
			$money = floatval($_REQUEST['money']);
			$log_info = strim($_REQUEST['log_info']);
			
			if($deal_info['ips_bill_no']>0){
				if($remain_money>0){
					$url= APP_ROOT."/index.php?ctl=collocation&act=Transfer&pTransferType=1&deal_id=".$deal_id."&ref_data=".$loan_data['repay_start_time']; 
 	 				 app_redirect($url);
				}else{
					$this->error("筹款发放完成");
				}
				
			}
			if($money<=0||$money>$remain_money)
			{
				$this->error("金额出错");
			}
			else
			{
				if($deal_info['user_id']>0)
				{
					if($deal_info['ips_bill_no']>0){
						
					}else{
						require_once APP_ROOT_PATH."system/libs/user.php";
						if($log_info=="")$log_info = $deal_info['name']."项目筹款发放";
						modify_account(array("money"=>$money),$deal_info['user_id'],$log_info,array('money_type'=>22));
						$log['deal_id'] = $deal_info['id'];
						$log['money'] = $money;
						$log['create_time'] = get_gmtime();
						$log['log_info'] = $log_info;
						$model->add($log);
						save_log($log_info.$money,1);
						
						send_pay_success($log_info);
						
	 					$this->success("筹款发放成功");
					}
 				}
				else
				{
					$this->error("管理员创建项目，无需发放筹款");
				}
			}
			
		}
		else
		{
			$this->error("项目不存在");
		}
	}
	
	public function del_pay_log()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("DealPayLog")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = format_price($data['money']);	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id.":".$info;
				$list = M("DealPayLog")->where ( $condition )->delete();				
				if ($list!==false) {					
					
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	//项目日志
	public function deal_log()
	{
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		$this->assign("deal_info",$deal_info);
		
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];	
			$model = D ("DealLog");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
		}
		
		$this->display();
	}
	
	public function del_deal_log()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );		
				$condition_log = array ('log_id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("DealLog")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id."的日志:".$info;
				$list = M("DealLog")->where ( $condition )->delete();	
							
				if ($list!==false) {		
					$GLOBALS['db']->query("update ".DB_PREFIX."deal set log_count = log_count - ".intval($list)." where id = ".$deal_id);			
					M("DealComment")->where($condition_log)->delete();
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	
	public function batch_refund()
	{
		$page = intval($_REQUEST['page']);

		$page=($page<=0)?1:$page;

		$page_size = 100;
		$deal_id = intval($_REQUEST['id']);
		
		$limit = (($page-1)*$page_size).",".$page_size;
		
		$deal_info = M("Deal")->where("id=".$deal_id." and is_delete = 0 and is_effect = 1 and is_success = 0 and end_time <>0 and end_time <".get_gmtime())->find();
		if(!$deal_info)
		{
			$this->error("该项目不能批量退款");
		}
		else
		{
			require_once APP_ROOT_PATH."system/libs/user.php";
			$refund_order_list = M("DealOrder")->where("deal_id=".$deal_id." and is_refund = 0 and order_status = 3")->limit($limit)->findAll();
			foreach($refund_order_list as $k=>$v)
			{
				$GLOBALS['db']->query("update ".DB_PREFIX."deal_order set is_refund = 1 where id = ".$v['id']);
				if($GLOBALS['db']->affected_rows()>0)
				{	
					modify_account(array("money"=>($v['online_pay']+$v['credit_pay'])),$v['user_id'],$v['deal_name']."退款");
					//退回积分
					if($v['score'] >0)
	 				{
						$log_info=$v['deal_name']."退款，退回".$v['score']."积分";
						modify_account(array("score"=>$v['score']),$v['user_id'],$log_info);
	 				}
					
					//扣掉购买时送的积分和信用值
					$sp_multiple=unserialize($v['sp_multiple']);
					if($v['score_multiple']>0)
					{
						$score=intval($v['total_price']*$sp_multiple['score_multiple']);
						$log_info=$v['deal_name']."退款，扣掉".$score."积分";
						modify_account(array("score"=>"-".$score),$v['user_id'],$log_info);
					}	
					if($sp_multiple['point_multiple']>0)
					{
						$point=intval($v['total_price']*$sp_multiple['point_multiple']);
						$log_info=$v['deal_name']."退款，扣掉".$point."信用值";
						modify_account(array("point"=>"-".$point),$v['user_id'],$log_info);
					}
					
					//抽奖订单 把抽奖号标为已退款
					if($v['type'] ==3)
					{
						$GLOBALS['db']->query("update ".DB_PREFIX."deal_order_lottery set is_winner=3 where order_id=".intval($v['id'])."");
					}
					
				}
			}
			
			//同步商品记录
			syn_deal($deal_info['id']);
			$deal_item_list=M("DealItem")->where("deal_id=".intval($deal_info['id']))->findAll();
			foreach($deal_item_list as $k=>$v)
			{									
				$deal_item['support_count'] = intval($GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_order where deal_id = ".$v['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($v['id'])));
				$deal_item['support_amount'] = floatval($GLOBALS['db']->getOne("select sum(deal_price) from ".DB_PREFIX."deal_order where deal_id = ".$v['deal_id']." and order_status=3 and is_refund=0 and deal_item_id=".intval($v['id'])));
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_item", $deal_item, $mode = 'UPDATE', "id=".intval($v['id']), $querymode = 'SILENT');
			}

			$remain = M("DealOrder")->where("deal_id=".$deal_id." and is_refund = 0 and order_status = 3")->count();
			if($remain==0)
			{
				$jump_url = u("Deal/online_index");
				$this->assign("jumpUrl",$jump_url);
				M("Deal")->where("id=".$deal_info['id'])->setField("deal_extra_cache","");
				M("DealLog")->where("deal_id=".$deal_info['id'])->setField("deal_info_cache","");
				$this->success("批量退款成功");
			}
			else
			{
				$jump_url = u("Deal/batch_refund",array("id"=>$deal_id,"page"=>$page+1));
				$this->assign("jumpUrl",$jump_url);
				$this->success("批量退款中，请勿刷新页面，剩余".$remain."条订单未退款");
			}
			
		}
		
	}
	function deal_update($deal_id){
		$deal=$GLOBALS['db']->getRow("select * from  ".DB_PREFIX."deal where id=$deal_id");
 		$now_time=get_gmtime();
 		if(($deal['begin_time']<$now_time||$deal['end_time']<$now_time)&&($deal['invote_money']>0||$deal['virtual_price']>0||$deal['support_amount']>0)){
 			// $this->error("项目已经开始无法编辑");
		} 
	}
	
	function sharefee_list(){
		$deal_id=intval($_REQUEST['deal_id']);
		$deal_info = M("Deal")->getById($deal_id);
		$map['deal_id'] = $deal_id;
		$user_id=intval($_REQUEST['user_id']);
		$user_name=strim($_REQUEST['user_name']);
		$deal_item_id=intval($_REQUEST['deal_item_id']);
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		//搜索条件
		if(isset($_REQUEST['share_status']))
		{
			$share_status = intval($_REQUEST['share_status']);
			if($share_status == -1)
				unset($_REQUEST['share_status']);
			else
				$map['share_status']=$share_status;
		}
		else
			$share_status=-1;
		if($user_id>0)
			$map['user_id'] = $user_id;
		else
			unset($user_id);
			
		if($deal_item_id >0)
			$map['deal_item_id'] = $deal_item_id;
		if($user_name !='')
			$map['user_name'] = array('like','%'.$user_name.'%');
		
		$map['share_fee'] = array('gt',0);
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		//子项目列表
		if($deal_info)
		{
			$model = D ("DealOrder");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
			
			$deal_item_list=D('DealItem')->where("deal_id=".intval($deal_info['id']))->findAll();
		}
		
		//分红情况
		$share_fee_total =  D ("DealOrder")->where("deal_id=".$deal_id."")->sum("share_fee");
		$share_fee_issue =  D ("DealOrder")->where("deal_id=".$deal_id." and share_status=1 ")->sum("share_fee");
		$this->assign("share_fee_total",$share_fee_total);
		$this->assign("share_fee_issue",$share_fee_issue);
		
		$this->assign("share_status",$share_status);
		$this->assign("user_id",$user_id);
		$this->assign("user_name",$user_name);
		$this->assign("deal_item_id",$deal_item_id);
		$this->assign("deal_info",$deal_info);
		$this->assign("deal_item_list",$deal_item_list);
		$this->assign("back_pay_log",u("Deal/pay_log",array("id"=>$deal_info['id'])));
		$this->assign("back_deal",u("Deal/online_index"));
		$this->display();
	}
	public function deal_vote()
	{
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		if($deal_info['is_success'] ==0){
			$this->error("众筹买房未成功，不能添加投票");
		}
		$this->assign("deal_info",$deal_info);
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];		
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
			$name=$this->getActionName();
			$model = D ("DealVote");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
		}
		$this->display();
	}
	public function add_deal_vote()
	{
		$now=get_gmtime();
		$deal_id = intval($_REQUEST['id']);
		$deal_info = M("Deal")->getById($deal_id);
		$this->assign("deal_info",$deal_info);
		$deal_vote = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_vote where deal_id= $deal_id and end_time > $now or (end_time < $now and status=1 ) order by id desc");
		if($deal_info['type'] ==2){
			if($deal_vote !=0)
			{
				$this->error("投票未结束或者投票同意，不能添加新的投票");
			}
		}
		$this->display();
	}
	public function insert_deal_vote() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M("DealVote")->create ();

		//开始验证有效性
		$this->assign("jumpUrl",u("Deal"."/add_deal_vote",array("id"=>$data['deal_id'])));
		if(!check_empty($data['money']))
		{
			$this->error("请输入卖出金额");
		}	
		$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
 		$data['create_time'] = get_gmtime();
		// 更新数据
		
		$list=M("DealVote")->add($data);
		$log_info =  "项目ID".$data['deal_id'].":".format_price($data['money']);	
		if (false !== $list) {
			//成功提示
			M("Deal")->where("id=".$data['deal_id'])->setField("deal_extra_cache","");
			save_log($log_info.L("INSERT_SUCCESS"),1);
			syn_deal($data['deal_id']);
			syn_deal_status($data['deal_id']);
			$this->success(L("INSERT_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("INSERT_FAILED"),0);
			$this->error(L("INSERT_FAILED"));
		}
		
	}
	
	public function edit_deal_vote()
	{
		$id = intval($_REQUEST ['id']);
		$condition['id'] = $id;		
		$vo = M("DealVote")->where($condition)->find();
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
		$this->assign ( 'vo', $vo );
		$this->display();
	}
	public function update_deal_vote() {
		B('FilterString');
		$ajax = intval($_REQUEST['ajax']);
		$data = M("DealVote")->create ();
		$data['moeny']=format_price($data['money']);
		//开始验证有效性
		$this->assign("jumpUrl",u("Deal"."/edit_deal_vote",array("id"=>$data['id'])));
		if(!check_empty($data['moeny']))
		{
			$this->error("请输入卖出金额");
		}	
		$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
		$data['create_time'] = get_gmtime();
		// 更新数据
		$this->deal_update(intval($data['deal_id']));
		$list=M("DealVote")->save($data);
		$log_info =  "项目ID".$data['deal_id'].":".format_price($data['money']);	
		if (false !== $list) {
			if($data['virtual_person']>0){
				
			}
			//成功提示
			
			M("Deal")->where("id=".$data['deal_id'])->setField("deal_extra_cache","");
			//end 处理图片
			save_log($log_info.L("UPDATE_SUCCESS"),1);
			syn_deal($data['deal_id']);
			syn_deal_status($data['deal_id']);
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			save_log($log_info.L("UPDATE_FAILED"),0);
			$this->error(L("UPDATE_FAILED"));
		}
	}
	
	public function del_deal_vote()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("DealVote")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = format_price($data['money']);	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id.":".$info;
				$list = M("DealVote")->where ( $condition )->delete();				
				if ($list!==false) {					
					M("Deal")->where("id=".$deal_id)->setField("deal_extra_cache","");
					syn_deal($deal_id);
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	//deal_vote_log 众筹买房投票详细
	public function deal_vote_log()
	{
		$deal_vote_id = intval($_REQUEST['id']);
		$deal_vote_info = M("DealVote")->getById($deal_vote_id);
		$this->assign('deal_vote_info',$deal_vote_info);
		if($deal_vote_info)
		{
			$map['deal_vote_id'] = $deal_vote_info['id'];		
			$model = D ("DealVoteLog");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
		}
		$this->display();
	}
	public function del_deal_vote_log()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );		
				$rel_data = M("DealVoteLog")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$info[] = $data['id'];	
				}
				if($info) $info = implode(",",$info);
				$list = M("DealVoteLog")->where ( $condition )->delete();		
				if ($list!==false) {		
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
			}
	}
	public function download(){
		$url=strim($_REQUEST['file']);
		if($url){
			header("Location: ".$url);
			exit;
		}else{
			return false;
		}
	}

	//项目分红列表
	public function user_bonus()
	{
		$deal_id = intval($_REQUEST['deal_id']);
		$type = intval($_REQUEST['type']);
		$deal_info = M("Deal")->getById($deal_id);
		$this->assign("deal_info",$deal_info);
		if($deal_info)
		{
			$map['deal_id'] = $deal_info['id'];	
			$map['type'] =$type;
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
			$name=$this->getActionName();
			$model = D ("UserBonus");
			if (! empty ( $model )) {
				$this->_list ( $model, $map );
			}
		}
		
		$this->display();
	
	}
	//项目分期详细
	public function edit_user_bonus()
	{
		$id = intval($_REQUEST['id']);
		$condition['id'] = $id;		
		$vo = M(UserBonus)->where($condition)->find();
		$vo['begin_time'] = $vo['begin_time']!=0?to_date($vo['begin_time']):'';
		$vo['end_time'] = $vo['end_time']!=0?to_date($vo['end_time']):'';
		$this->assign ( 'vo', $vo );
		
		$user_bonus_list = M("UserBonusList")->where("user_bonus_id=".$vo['id'])->order("id asc")->findAll();
		$this->assign("user_bonus_list",$user_bonus_list);
		
		$this->display();
	}
	public function update_user_bonus()
	{
		B('FilterString');
		$data = M("UserBonus")->create();
		$data['begin_time'] = trim($data['begin_time'])==''?0:to_timespan($data['begin_time']);
		$data['end_time'] = trim($data['end_time'])==''?0:to_timespan($data['end_time']);
	
		$list=M("UserBonus")->save ($data);
		if (false !== $list) {
			$this->success(L("UPDATE_SUCCESS"));
		} else {
			//错误提示
			$this->error(L("UPDATE_FAILED"));
		}
	}
	public function del_user_bonus()
	{
		$ajax = intval($_REQUEST['ajax']);
		$id = $_REQUEST ['id'];
		if (isset ( $id )) {
				$condition = array ('id' => array ('in', explode ( ',', $id ) ) );		
				$condition1 = array ('user_bonus_id' => array ('in', explode ( ',', $id ) ) );				
				$rel_data = M("UserBonus")->where($condition)->findAll();				
				foreach($rel_data as $data)
				{
					$deal_id = $data['deal_id'];
					$info[] = format_price($data['price']);	
				}
				if($info) $info = implode(",",$info);
				$info = "项目ID".$deal_id.":".$info;
				
				$list = M("UserBonus")->where ( $condition )->delete();		
				$list1 = M("UserBonusList")->where ( $condition1 )->delete();						
				if ($list!==false && $list1!==false) {					
					
					save_log($info.l("FOREVER_DELETE_SUCCESS"),1);
					$this->success (l("FOREVER_DELETE_SUCCESS"),$ajax);
				} else {
					save_log($info.l("FOREVER_DELETE_FAILED"),0);
					$this->error (l("FOREVER_DELETE_FAILED"),$ajax);
				}
			} else {
				$this->error (l("INVALID_OPERATION"),$ajax);
		}
	}
	public function submit_user_bonus(){
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$now=get_gmtime();
		$sql_str = "select 
					ub.id as 分红编号,
					d.name as 项目名称,
					d.limit_price as 融资金额,
					ub.year as 分红年度,
					ub.number as 分红期数,
					ub.money as 分红金额,
					ub.average_monthly_returns as 平均月回报率,
					ub.average_annualized_return as 平均年回报率,
					ub.begin_time as 开始时间,
					ub.end_time as 结束时间,
					ub.status as 状态
					from ".DB_PREFIX."deal  as d LEFT JOIN ".DB_PREFIX."user_bonus as ub on d.id = ub.deal_id
					where  d.is_delete = 0 
					and d.is_effect = 1 and d.is_success = 1 
					and (d.stock_type = 1 or (d.stock_type = 3 and  d.share_fee_descripe != '')) and  ub.status = 0 and ub.type = 0 and d.type = 1 and d.end_time < ".$now." and 1 = 1 ";
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		
		
		$model = D('Deal');
		$voList = $this->_Sql_list($model, $sql_str);
	//print_r($voList);exit;
		$this->display();
	}
	public function submit_fixed_interest(){
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$now=get_gmtime();
		$sql_str = "select 
					ub.id as 回报编号,
					d.name as 项目名称,
					d.limit_price as 融资金额,
					ub.year as 回报年度,
					ub.number as 回报期数,
					ub.money as 回报金额,
					ub.return_cycle as 回报周期,
					ub.average_annualized_return as 平均年回报率,
					ub.begin_time as 开始时间,
					ub.end_time as 结束时间,
					ub.status as 状态
					from ".DB_PREFIX."deal  as d LEFT JOIN ".DB_PREFIX."user_bonus as ub on d.id = ub.deal_id
					where  d.is_delete = 0 
					and d.is_effect = 1 and d.is_success = 1 
					and (d.stock_type = 2 or d.stock_type = 3) and  ub.status = 0 and ub.type = 1 and d.type = 1 and d.end_time < ".$now." and 1 = 1 ";
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		
		
		$model = D();
		$voList = $this->_Sql_list($model, $sql_str);
//	print_r($sql_str);exit;
		$this->display();
	}
	public function submit_buy_house_earnings(){
		if(intval($_REQUEST['action_id'])!='')
		{
			$action_id= intval($_REQUEST['action_id']);
		}
		$this->assign('action_id',$action_id);
		$now=get_gmtime();
		$sql_str = "select 
					ub.id as 收益编号,
					d.name as 项目名称,
					d.limit_price as 融资金额,
					ub.year as 收益年度,
					ub.number as 收益期数,
					ub.money as 收益金额,
					ub.return_cycle as 收益周期,
					ub.average_annualized_return as 平均年收益率,
					ub.begin_time as 开始时间,
					ub.end_time as 结束时间,
					ub.status as 状态
					from ".DB_PREFIX."deal  as d LEFT JOIN ".DB_PREFIX."user_bonus as ub on d.id = ub.deal_id
					where  d.is_delete = 0 
					and d.is_effect = 1 and d.is_success = 1 
					and  ub.status = 0 and ub.type = 2 and d.type = 2 and d.end_time < ".$now." and 1 = 1 ";
		if(trim($_REQUEST['name'])!='')
		{
			$sql_str .= " and d.name like '%".trim($_REQUEST['name'])."%'  ";	
		}
		
		
		$model = D();
		$voList = $this->_Sql_list($model, $sql_str);
		$this->display();
	}
	public function get_lottery()
	{
		$deal_id=intval($_REQUEST['id']);
		$user_name=strim($_REQUEST['user_name']);
		$lottery_sn=strim($_REQUEST['lottery_sn']);
	
		$deal_info=M("deal")->where("id=".$deal_id)->find();
		if(!$deal_info)
			$this->error("未找到项目");

		if($user_name !='')
		{
			$map['user_name']=array("like","%".$user_name."%");
		}
		if($lottery_sn !='')
		{
			$map['lottery_sn']=$lottery_sn;
		}
		
		$map['deal_id'] = $deal_id;
		$map['is_winner'] = array("lt",2);			
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		
		$model = D ("dealOrderLottery");
		if (! empty ( $model )) {
			$this->_list ( $model, $map , 'is_winner');
		}
		
		$this->assign("user_name",$user_name);
		$this->assign("lottery_sn",$lottery_sn);
		$this->assign("deal_info",$deal_info);
		$this->display ();
	}
	
	public function images_add($deal_id)
	{
		$imgs=array($_REQUEST['image1'],$_REQUEST['image2'],$_REQUEST['image3'],$_REQUEST['image4'],$_REQUEST['image5']);
		foreach($imgs as $k=>$v){
			if($v != '')
			{
				$img_data['deal_id']=$deal_id;
				$img_data['image']=$v;
				M('deal_image')->add($img_data);
			}
		}
	}
}
?>