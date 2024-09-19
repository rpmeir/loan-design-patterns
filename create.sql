
drop schema if exists loan cascade;

create schema if not exists loan;

create table if not exists loan.loans (
    code uuid,
    amount numeric,
    period integer,
    rate numeric,
    type text,
    salary numeric
);

create table if not exists loan.installments (
    loan_code uuid,
    number integer,
    amount numeric,
    interest numeric,
    amortization numeric,
    balance numeric
);
