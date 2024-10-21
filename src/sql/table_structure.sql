use footscape_db;

create table
  users (
    id int not null auto_increment,
    first_name varchar(50),
    last_name varchar(50),
    username varchar(10) not null unique,
    email varchar(50) not null unique,
    password varchar(50) not null,
    user_mobile varchar(8) check (user_mobile regexp '^[0-9] {8}$'),
    primary key (id),
  );

create table
  products (
    product_id int not null auto_increment,
    name varchar(100) not null,
    description varchar(255) not null,
    category varchar(20) not null,
    gender varchar(10) default unisex,
    price decimal(10, 2) not null,
    size int (2) not null check (size between 1 and 20),
    quantity int (10) default 0,
    primary key (product_id),
  );

create table
  cart (
    id int not null auto_increment,
    user_id int not null,
    quantity int (3) not null check (quantity > 0),
    product_id int not null,
    primary key (id),
    foreign key (product_id) references products (product_id),
    foreign key (user_id) references users (id),
  );

create table
  orders (
    id int not null auto_increment,
    user_id int not null,
    total_amount decimal(10, 2) not null,
    address varchar(150) not null,
    postal_code varchar(6) check (postal_code regexp '^[0-9]{6}$'),
    receiver_name varchar(10),
    receiver_mobile int (8) check (user_mobile between 10000000 and 99999999),
    primary key (id),
    foreign key (user_id) references users (id),
  );

create table
  order_items (
    id int not null auto_increment,
    order_id int not null,
    product_id int not null,
    quantity int not null check (quantity >= 0),
    price decimal(10, 2) not null,
    created_at timestamp default current_timestamp,
    primary key (id),
    foreign key (id) references cart (user_id),
    foreign key (order_id) references orders (id),
    foreign key (product_id) references products (product_id),
  );