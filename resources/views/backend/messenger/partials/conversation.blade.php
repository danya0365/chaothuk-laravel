<div class="chat-body p-4 flex-1 overflow-y-scroll" x-ref="messageElement" id="messageElement">
    <template x-for="conversation in conversations" :key="conversation.id">
        <div>
            <template x-if="isCustomer(participants, conversation.author.id)">
                <div class="flex flex-row justify-start" x-init="updateConversationSeen(conversation)">
                    <div class="messages text-sm text-green-700 grid grid-flow-row gap-2">
                        <div class="flex items-center group">
                            <template x-if="conversation.type === 'text'">
                                <p x-text="conversation.content"
                                    class="px-6 py-3 rounded-t-full rounded-r-full bg-green-800 max-w-xs lg:max-w-md text-gray-200">
                                </p>
                            </template>
                            <template x-if="conversation.type === 'url'">
                                <div class="flex flex-row items-center gap-2">
                                    <a target="_blank" x-bind:href="conversation.content"
                                        class="px-6 py-3 rounded-t-full rounded-r-full bg-green-800 max-w-xs lg:max-w-md text-gray-200 underline">
                                        เปิดไฟล์แนบ
                                    </a>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="!isCustomer(participants, conversation.author.id)">
                <div class="flex flex-row justify-end">
                    <div class="messages text-sm text-white grid grid-flow-row gap-2">
                        <div class="flex items-center flex-row-reverse group">
                            <template x-if="conversation.type === 'text'">
                                <p x-text="conversation.content"
                                    class="px-6 py-3 rounded-t-full rounded-l-full bg-lime-700 max-w-xs lg:max-w-md">
                                </p>
                            </template>
                            <template x-if="conversation.type === 'url'">
                                <div class="flex flex-row items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                    </svg>
                                    <a target="_blank" x-bind:href="conversation.content"
                                        class="px-6 py-3 rounded-t-full rounded-l-full bg-lime-700 max-w-xs lg:max-w-md underline">
                                        เปิดไฟล์แนบ
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="isCustomer(participants, conversation.author.id)">
                <div class="flex flex-row justify-start">
                    <p x-text="dateFormat(conversation.created_at)" class="p-2 text-left text-xs text-gray-300"></p>
                </div>
            </template>
            <template x-if="!isCustomer(participants, conversation.author.id)">
                <div class="flex flex-row justify-end items-center">
                    <p x-text="dateFormat(conversation.created_at)" class="p-2 text-right text-xs text-gray-300"></p>

                    <template x-if="conversation.seen_at">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 text-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </template>
                    <template x-if="!conversation.seen_at">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 text-gray-200">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>
