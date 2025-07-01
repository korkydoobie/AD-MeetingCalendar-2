CREATE TABLE IF NOT EXISTS meetings (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(100) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE
);
