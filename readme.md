# Knowledge back-end document V1.0
## API principle
- All APIs start with `domain.com/api/`
- APIs are separated into two parts.For example: `domain.com/api/part_1/part_2`
    - `part_1` refers to model name.For example:`user`, `question`
    - `part_2` refers to function. For example:`reset_password`
- CRUD
    - For each model there are CRUD functions, which are `add`, `remove`, `change` and `read`
 
 ## Model
 ### Question
 #### `add`
 - Authority: logged in
 - Parameters:
    - mandatory: `title`
    - selectable: `desc`
 ####  `change`
 - Authority: logged in and the owner of the question
 - Parameters:
    - mandatory: `question_id`
    - selectable: `title`,`desc`(content of the question)
  