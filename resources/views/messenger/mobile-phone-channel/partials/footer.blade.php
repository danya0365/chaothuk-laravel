<div class="chat-footer flex-none">
    <form class="flex flex-row items-center p-4" x-on:submit.prevent="onSubmit()">
        <input type="file" class="sr-only" id="attach_file" onclick="this.value = null"
            x-on:change="files = Object.values($event.target.files)">
        <label class="flex-shrink-0 focus:outline-none mx-2 block cursor-pointer my-2 w-6 h-6" for="attach_file">
            <span class=" text-teal-600 hover:text-teal-700 w-full h-full">
                <svg viewBox="0 0 20 20" class="w-full h-full fill-current hidden">
                    <path
                        d="M0,6.00585866 C0,4.89805351 0.893899798,4 2.0048815,4 L5,4 L7,2 L13,2 L15,4 L17.9951185,4 C19.102384,4 20,4.89706013 20,6.00585866 L20,15.9941413 C20,17.1019465 19.1017876,18 18.0092049,18 L1.99079514,18 C0.891309342,18 0,17.1029399 0,15.9941413 L0,6.00585866 Z M10,16 C12.7614237,16 15,13.7614237 15,11 C15,8.23857625 12.7614237,6 10,6 C7.23857625,6 5,8.23857625 5,11 C5,13.7614237 7.23857625,16 10,16 Z M10,14 C11.6568542,14 13,12.6568542 13,11 C13,9.34314575 11.6568542,8 10,8 C8.34314575,8 7,9.34314575 7,11 C7,12.6568542 8.34314575,14 10,14 Z" />
                </svg>
                <svg viewBox="0 0 20 20" class="w-full h-full fill-current">
                    <path
                        d="M10,1.6c-4.639,0-8.4,3.761-8.4,8.4s3.761,8.4,8.4,8.4s8.4-3.761,8.4-8.4S14.639,1.6,10,1.6z M15,11h-4v4H9  v-4H5V9h4V5h2v4h4V11z" />
                </svg>
            </span>
        </label>
        <div class="relative flex-grow">
            <template x-if="files === null">
                <input x-model="message"
                    class="rounded-full py-2 px-3 w-full border placeholder-teal-600 border-green-800 focus:border-green-700 bg-green-800 focus:bg-green-900 focus:outline-none text-green-200 focus:shadow-md transition duration-300 ease-in"
                    type="text" value="" placeholder="พิมพ์ข้อความที่นี่" />
            </template>
            <template x-if="files !== null">
                <label>
                    <input disabled
                        class="rounded-full py-2 px-3 w-full border border-green-800 focus:border-green-700 bg-green-800 focus:bg-green-900 focus:outline-none text-green-200 focus:shadow-md transition duration-300 ease-in"
                        type="text" :value="files.map(file => file.name).join(', ')" />
                    <button type="button" x-on:click="files = null"
                        class="absolute top-0 right-0 mt-2 mr-3 flex-shrink-0 focus:outline-none block text-red-600 hover:text-red-700 w-6 h-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-full h-full fill-current">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>

                    </button>
                </label>
            </template>
        </div>
        <button type="submit"
            class="flex-shrink-0 focus:outline-none mx-2 block text-teal-600 hover:text-teal-700 w-6 h-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-full h-full">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
            </svg>
        </button>
    </form>
</div>
