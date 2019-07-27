/* 
 *  数据表修改
 */

/* contract  */
alter table contract change status status tinyint(3) not null default 1 
comment'项目状态：0 已作废；1 跟踪中；2 已签约；3 派单中；4 开发中；5 收款期；9 已完结；10 转售后；';


/* customer  */
alter table customer add admin_name char(32) tinyint(3) comment'业务员名' after admin_id;


/* project  */
alter table project add admin_name char(32) comment'业务员名' after admin_id;
update project left join admin on project.admin_id = admin.id set project.admin_name = admin.name where project.id < 1000;

alter table project add customer_name char(64) comment'客户名' after customer_id;
update project left join customer on project.customer_id = customer.id set project.customer_name = customer.username where project.id < 1000;

alter table project change status status tinyint(3) default 1 comment'项目状态：0 已作废；1 跟踪中；2 已签约；3 派单中；4 开发中；5 收款期；9 已完结；10 转售后；';
alter table project add develop_date date comment'开发开始时间' after type_id;
alter table project add deliver_date date comment'交付时间' after develop_date;

alter table project add develop_date date comment'开发开始时间' after type_id;
alter table project add deliver_date date comment'交付时间' after develop_date;
alter table project add note text comment'需求内容' after remarks;
alter table project change remarks remarks text comment'项目说明';




/* records  */
CREATE TABLE IF NOT EXISTS `record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned NOT NULL comment '项目id',
  `projet_name` varchar(255) comment '项目名',
  `admin_id` int unsigned NOT NULL comment'记录人id',
  `admin_name` char(32)  comment'记录人',
  `customer_id` int unsigned NOT NULL comment'客户id',
  `customer_name` char(32) comment'客户名',
  `process` text  comment'沟通过程',
  `result` text  comment'沟通结果',
  `question` text  comment'遗留问题',
  `create_time` int(10) comment '添加时间',
  `update_time` int(10) comment '修改时间',
  PRIMARY KEY (`id`),
  key `projectid` (`project_id`),
  key `adminid` (`admin_id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8 comment'项目沟通记录表';




