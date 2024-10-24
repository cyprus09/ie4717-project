use footscape_db;

drop table if exists users;

create table
  if not exists users (
    id int not null unsigned auto_increment,
    first_name varchar(50) default '' not null,
    last_name varchar(50) default '' not null,
    username varchar(10) default '' not null unique,
    email varchar(50) default '' not null unique,
    password varchar(50) not null,
    user_mobile varchar(8) check (user_mobile regexp '^[0-9]{8}$'),
    primary key (id)
  );

drop table if exists products;

create table
  if not exists products (
    product_id int unsigned not null auto_increment,
    name varchar(100) default '' not null,
    description varchar(255) default '' not null,
    category varchar(20) not null,
    gender varchar(10) default 'unisex',
    price decimal(10, 2) not null,
    size int (2) not null check (size between 1 and 20),
    quantity int (10) default 0,
    primary key (product_id)
  );

drop table if exists cart;

create table
  if not exists cart (
    id int unsigned not null auto_increment,
    user_id int unsigned not null,
    quantity int (3) unsigned not null check (quantity >= 0),
    product_id int unsigned not null,
    primary key (id),
    foreign key (product_id) references products (product_id),
    foreign key (user_id) references users (id)
  );

drop table if exists orders;

create table
  if not exists orders (
    id int unsigned not null auto_increment,
    user_id int unsigned default '' not null,
    total_amount decimal(10, 2) not null check (total_amount > 0),
    address varchar(150) default '' not null,
    postal_code varchar(6) check (postal_code regexp '^[0-9]{6}$'),
    receiver_name varchar(10) default '',
    receiver_mobile int (8) unsigned check (receiver_mobile between 10000000 and 99999999),
    primary key (id),
    foreign key (user_id) references users (id)
  );

drop table if exists order_items;

create table
  if not exists order_items (
    id int unsigned not null auto_increment,
    order_id int unsigned not null,
    product_id int unsigned not null,
    quantity int not null check (quantity >= 0),
    price decimal(10, 2) not null check (price > 0),
    created_at timestamp default current_timestamp,
    primary key (id),
    foreign key (id) references cart (user_id),
    foreign key (order_id) references orders (id),
    foreign key (product_id) references products (product_id)
  );