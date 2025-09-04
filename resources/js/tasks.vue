<template>
    <div>
        <div v-if="tasks === null || tasks.length === 0">
            Список пуст
        </div>
        <div v-else>

            <table class="table is-fullwidth">
                <thead>
                    <tr>
                        <td>
                            ID
                        </td>
                        <td>
                            Заголовок
                        </td>
                        <td>
                            Статус
                        </td>
                        <td>

                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(task, i) in tasks">
                        <td class="has-cursor-pointer" @click="show(task)">
                            {{ task.id }}
                        </td>
                        <td class="has-cursor-pointer" @click="show(task)">
                            {{ task.title }}
                        </td>
                        <td class="has-cursor-pointer" @click="show(task)">
                            {{ task.status }}
                        </td>
                        <td class="has-cursor-pointer" @click="show(task)">
                            {{ task.created_at }}
                        </td>
                        <td>
                            <button class="button" @click="update(task)">
                                <span class="material-icons">
                                    edit
                                </span>
                            </button>
                            <button class="button" @click="destroy(task, i)">
                                <span class="material-icons">
                                    delete
                                </span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button class="button create-button" @click="create()">
            <span class="material-icons">
                playlist_add
            </span>
        </button>

        <div class="modal is-active" v-if="openWindow">
            <div class="modal-background" @click="closeWindow()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                <p class="modal-card-title">{{ showTask.title ? showTask.title : 'Новая задача'}}</p>
                <button class="delete" aria-label="close" @click="closeWindow()"></button>
                </header>
                <section class="modal-card-body">
                    <div class="field">
                        <label class="label"></label>
                        <input type="text" v-model="showTask.title" class="input">
                    </div>
                    <div class="field">
                        <label class="label"></label>
                        <textarea v-model="showTask.description" class="textarea"></textarea>
                    </div>
                    <div class="field">
                        <label class="label"></label>

                        <input type="text" v-model="showTask.status" class="input">
                    </div>
                    
                </section>
                <footer class="modal-card-foot">
                <div class="buttons">
                    <button class="button is-success" @click="save()">Сохранить</button>
                    <button class="button" @click="closeWindow()">Отменить</button>
                </div>
                </footer>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    
    data() {
        return {

            /**
             * список задач
             */
            tasks:null,

            /**
             * задача для открытия в окне
             */
            showTask: null,

            /**
             * окно
             */
            openWindow: false,

        }
    },

    async created() {
        
        // забираем список задач
        let response = await axios.get("/api/tasks").catch(error => {
            if (error.response.data) {
                
                let errors = error.response.data;

                for (let i = 0; i < errors.length; i++) {
                    this.$toast(errors[i]['message']);
                }
                return;
            }

            this.$toast.error("Ошибка запроса задач")
        })

        this.tasks = response.data.tasks;
        
    },

    methods: {
        
        /**
         * запрашивает данные задачи
         * @param task 
         */
        show(task){

            axios.get("/api/tasks/"+task.id).then(response => {

                // заполняем переменную данными о задаче
                this.showTask = response.data.task;

                // открываем модальное окно
                this.openWindow = true;

            }).catch(error => {

                // обработка ошибок
                if (error.response.data) {
                    
                    let errors = error.response.data.errors;

                    for (let key in errors) {
                        this.$toast.error(errors[key]);
                    }
                    return;
                }

                this.$toast.error("Ошибка запроса задач")
            })
        },

        update(task){
            this.showTask = task;
            this.openWindow = true;

        },

        create(){
            this.showTask = {};
            this.openWindow = true;
        },

        /**
         * сохранение изменений
         * создание новой задачи
         */
        save(){

            let id = this.showTask.id ? this.showTask.id : "";

            let method = id ? "put" : "post";

            axios({
                url:"/api/tasks/"+id,
                method: method,
                data: this.showTask
            }).then(response => {

                // если задача создавалась
                if (!id) {

                    // просто добавляем ее в список задач
                    this.tasks.push(response.data.task);

                    // выводим сообщение
                    this.$toast.success(response.data.message);
                    
                    return;
                }

                // если задача обновлялась
                let task = response.data.task;

                // находим в массиве задачу по id 
                let i = this.tasks.findIndex(el => {
                    return el.id == task.id
                });

                // заменяем ее значения
                this.tasks[i] = task;

                // выводим сообщение
                this.$toast.success(response.data.message);

            }).catch(error => {
                // обработка ошибок 
                if (error.response.data) {
                    
                    let errors = error.response.data.errors;

                    for (let key in errors) {
                        this.$toast.error(errors[key]);
                    }
                    return;
                }
                this.$toast.error("Ошибка запроса задач")
            });

        },


        /**
         * удаление задачи
         * @param task сама задача
         * @param i индекс задачи в массиве
         */
        destroy(task, i){


            // подтверждение удаление
            if (!window.confirm("Вы уверены что хотите удалить задачу ID:"+task.id+" ?")) {
                return;
            }

             axios.delete("/api/tasks/"+task.id).then(response => {

                // вывод сообщения
                this.$toast.success(response.data.message);

                // удаление данных из массива
                this.tasks.splice(i, 1)

            }).catch(error => {
                // обработка ошибок
               if (error.response.data) {
                    
                    let errors = error.response.data.errors;

                    for (let key in errors) {
                        this.$toast.error(errors[key]);
                    }
                    return;
                }
                this.$toast.error("Ошибка запроса задач")
            });

        },

        /**
         * закрывает модальное окно 
         */
        closeWindow(){
            this.openWindow = false;
            this.showTask = null;
        }
    },
}
</script>

<style lang="scss">
    .create-button{
        position: fixed;
        bottom: 10px;
        right: 15px;
        z-index: 9;
    }
    .has-cursor-pointer{
        cursor: pointer;
    }
</style>