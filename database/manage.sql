/* 
 *  数据表修改
 */

/* contract  */
alter table contract change status status tinyint(3) not null default 1 
comment'项目状态：0 已作废；1 跟踪中；2 已签约；3 派单中；4 开发中；5 收款期；9 已完结；10 转售后；';

alter table contract add deleted_at int comment'软删除'; 
alter table tuomei.contract add input_id int comment'录入人id' after end_time;
alter table tuomei.contract add input_name char(32) comment'录入人名' after input_id;
alter table tuomei.contract change column `describe` `describe` text null comment'合同描述'; 

/* customer  */
alter table customer add admin_name char(32) comment'业务员名' after admin_id;
alter table customer add deleted_at int  comment'软删除'; 

/* project  */
alter table project add admin_name char(32) comment'业务员名' after admin_id;
update project left join admin on project.admin_id = admin.id set project.admin_name = admin.name where project.id < 1000;

alter table project add customer_name char(64) comment'客户名' after customer_id;
update project left join customer on project.customer_id = customer.id set project.customer_name = customer.username where project.id < 1000;

alter table project change status status tinyint(3) default 1 comment'项目状态：0 已作废；1 跟踪中；2 已签约；3 派单中；4 开发中；5 收款期；9 已完结；10 转售后；';

alter table project add develop_date date comment'开发开始时间' after type_id;
alter table project add deliver_date date comment'交付时间' after develop_date;
alter table project add note text comment'需求内容' after remarks;
alter table project change remarks remarks text comment'项目说明';
alter table project add deleted_at int comment'软删除'; 

alter table project add input_id int comment'录入人id' after is_bid;
alter table project add input_name char(32) comment'录入人名' after input_id;

alter table tuomei.project change payment_status payment_status tinyint default 0 comment'付款状态: 0 未付款、1 预付款已收、2 中款1已收、3 中款2已收、4 中款3已收、5 中款4已收、9尾款已收';
alter table tuomei.project change type_id type_id set('App', 'Web', '小程序', '公众号', '网站', '商城') comment'项目类型：App,Web,小程序,公众号,网站,商城' ;


/* records  */
CREATE TABLE IF NOT EXISTS `records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL comment '项目id',
  `project_name` varchar(255) comment '项目名',
  `input_id` int unsigned NOT NULL comment'记录人id',
  `input_name` char(32)  comment'记录人',
  `customer_id` int unsigned NOT NULL comment'客户id',
  `customer_name` char(32) comment'客户名',
  `process` text  comment'沟通过程',
  `result` text  comment'沟通结果',
  `question` text  comment'遗留问题',
  `record_at` int comment '沟通时间',
  `create_at` int comment '添加时间',
  `update_at` int comment '修改时间',
  `deleted_at` int comment '软删除',
  PRIMARY KEY (`id`),
  key `projectid` (`project_id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8 comment'项目沟通记录表';


/* packages  */
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL comment '项目id',
  `project_name` varchar(255) comment '项目名',
  `input_id` int unsigned NOT NULL comment'添加人id',
  `input_name` char(32)  comment'添加人',
  `package_app` varchar(255)  comment'app程序包',
  `app_size` decimal(10,2) comment'app程序包大小',
  `package_web` varchar(255)  comment'web程序包',
  `web_size` decimal(10,2) comment'web程序包大小',
  `package_sql` varchar(255)  comment'数据库包',
  `sql_size` decimal(10,2) comment'数据库包大小',
  `remarks` text comment '备注信息',
  `created_at` int comment '添加时间',
  `updated_at` int comment '修改时间',
  `deleted_at` int comment '软删除',
  PRIMARY KEY (`id`),
  key `projectid` (`project_id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8 comment'项目程序包表';




