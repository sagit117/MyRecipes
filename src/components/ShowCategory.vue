<template>
	<div class="showCategory">
		<label>Выбрать категорию: </label>
    <select ref="listCategories">
			<option value="0">Все</option>
      <option ></option>
    </select>
    <template>
      <div class="addDiv">
        <img  src="ico/add.png" 
              class="add"  
              title="Добавить категорию">
      </div>
      <div class="newCategory" >
          <input  type="text" >
          <div>
            <button >Отмена</button>
            <button >Сохранить</button>
          </div>
      </div>
    </template>
	</div>
</template>

