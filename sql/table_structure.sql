use footscape_db;

-- dependent tables with foreign keys in order for correct deletion
drop table if exists order_items;

drop table if exists orders;

drop table if exists cart;

drop table if exists products;

drop table if exists users;

create table
  if not exists users (
    id int unsigned not null auto_increment,
    first_name varchar(50) not null default '',
    last_name varchar(50) not null default '',
    username varchar(20) not null unique,
    email varchar(50) not null unique,
    password varchar(500) not null,
    user_mobile varchar(8) check (user_mobile regexp '^[0-9]{8}$'),
    primary key (id)
  );

create table
  if not exists products (
    product_id int unsigned not null auto_increment,
    name varchar(100) not null default '',
    brand varchar(50) not null,
    description varchar(255) not null default '',
    category varchar(20) not null,
    gender varchar(10) not null default 'unisex',
    price decimal(10, 2) not null,
    size int not null check (size between 1 and 20),
    quantity int unsigned default 0,
    primary key (product_id)
  );

create table
  if not exists cart (
    id int unsigned not null auto_increment,
    user_id int unsigned not null,
    quantity int unsigned not null check (quantity >= 0),
    product_id int unsigned not null,
    primary key (id),
    foreign key (product_id) references products (product_id),
    foreign key (user_id) references users (id)
  );

create table
  if not exists orders (
    id int unsigned not null auto_increment,
    user_id int unsigned not null,
    total_amount decimal(10, 2) not null check (total_amount > 0),
    address varchar(150) not null default '',
    postal_code varchar(6) check (postal_code regexp '^[0-9]{6}$'),
    receiver_name varchar(50) not null default '',
    receiver_mobile varchar(8) check (receiver_mobile regexp '^[0-9]{8}$'),
    primary key (id),
    foreign key (user_id) references users (id)
  );

create table
  if not exists order_items (
    id int unsigned not null auto_increment,
    order_id int unsigned not null,
    product_id int unsigned not null,
    quantity int unsigned not null check (quantity >= 0),
    price decimal(10, 2) not null check (price > 0),
    created_at timestamp default current_timestamp,
    primary key (id),
    foreign key (order_id) references orders (id),
    foreign key (product_id) references products (product_id)
  );