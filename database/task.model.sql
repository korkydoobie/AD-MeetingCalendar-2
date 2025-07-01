CREATE TABLE IF NOT EXISTS tasks (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    meeting_id uuid REFERENCES meetings(id),
    assigned_to uuid REFERENCES users(id),
    title VARCHAR(150) NOT NULL,
    description TEXT,
    status VARCHAR(50) DEFAULT 'pending',
    due_date DATE
);
