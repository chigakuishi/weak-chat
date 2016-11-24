use weakchat;

drop table if exists data;
create table data(
  id integer primary key auto_increment,
  content varchar(1024) not null,
  name varchar(512) not null,
  hash varchar(10)
);
insert into data(content,name,hash) values
  ("はじめまして、管理者です。","admin","a0434bbffd"),
  ("指示のない破壊的なことはやめましょう","admin","a0434bbffd"),
  ("良識を持って行動してください","admin","a0434bbffd");

