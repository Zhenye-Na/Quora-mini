# Quora-mini Backend API docs v1.0.0

## General API

- API begins with `domain.com/api/...`
- API has two parts `domain.com/api/part1/part2`
    - "part1" includes model names like `user` or `question`
    - "part2" includes activities related to particular model
- CRUD
    - Each model contains four activities including: Create, Read, Update, Delete using function name: `add`, `read`, `change`, `remove`

## Model

- Answer
- Comment
- Question
- User

### Usage Example: Question

#### `add`

- Arguments:
    - `title`
    - `desc` (optional) description of particular question


#### `change`

- Arguments:
    - `id`: question_id
    - `title` (optional) description of particular question