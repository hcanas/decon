<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head} from "@inertiajs/vue3";
</script>

<template>
    <MainLayout>
        <section class="w-full md:max-w-7xl md:mx-auto px-3 md:px-0 py-6">
            <Head title="About Us" />

            <div class="flex flex-col md:flex-row items-center justify-center space-x-12">
                <div class="flex flex-col space-y-6">
                    <div class="flex flex-col items-center md:items-start">
                        <h1 class="uppercase text-4xl md:text-6xl font-bold leading-3">About</h1>
                        <h1 class="uppercase text-primary-500 text-5xl md:text-7xl font-bold leading-tight">EC PRIME</h1>
                        <h1 class="uppercase text-3xl md:text-5xl font-medium leading-3">Corporation</h1>
                    </div>
                    <div class="flex flex-col">
                        <h5 class="text-lg font-medium italic">Mission</h5>
                        <p class="text-justify">To excel in the realm of general trading within the Philippines, engaging in the procurement, distribution, and marketing of a diverse array of goods, commodities, and merchandise in compliance with local regulations. We commit ourselves to fostering growth, innovation, and sustainability in every transaction, ensuring value for our customers and contributing positively to the economic landscape of the Philippines.</p>
                    </div>
                    <div class="flex flex-col">
                        <h5 class=" text-lg font-medium italic">Vision</h5>
                        <p class="text-justify">To establish ourselves as a leader in the general trading sector, renowned for our integrity, quality, and comprehensive service offerings. We aspire to be a cornerstone of the Philippine economy, enhancing the flow of goods both domestically and internationally, and setting new standards for excellence and ethical business practices in the trading community.</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <img src="/images/about_us2.png" class="hidden md:inline h-[600px]" />
                </div>
            </div>

            <div class="flex flex-col items-center space-y-12 mt-24">
                <div class="flex items-center md:space-x-12">
                    <div class="md:w-[40rem] flex flex-col space-y-3">
                        <h5 class="text-2xl font-bold">DECON MEDICAL</h5>
                        <div class="flex flex-col">
                            <h5 class="text-lg font-medium italic">Mission</h5>
                            <p class="text-justify">To elevate healthcare standards in the Philippines by specializing in hemodialysis and diagnostics, ensuring every Filipino receives the highest quality of care. Through public-private partnerships with the government and the support of local government units, our centers are committed to making advanced healthcare services accessible and affordable, fostering a healthier nation. We are dedicated to reaching out and providing compassionate care, aiming to make a significant difference in the lives of the people we serve.</p>
                        </div>
                        <div class="flex flex-col">
                            <h5 class=" text-lg font-medium italic">Vision</h5>
                            <p class="text-justify">To be at the forefront of healthcare innovation in the Philippines, where our pioneering services in hemodialysis and diagnostics redefine patient care and wellness. We envision a future where our collaborative efforts with the government and the community ensure that every Filipino benefits from cutting-edge medical services. Our goal is to integrate these advancements into the fabric of Filipino society, setting new paradigms of compassion, quality, and efficiency in healthcare, making a healthier life accessible to all.</p>
                        </div>
                    </div>
                    <div>
                        <img src="/images/hemodialysis.jpg" class="hidden md:inline w-[60rem]" />
                    </div>
                </div>

                <div class="flex items-center md:space-x-12">
                    <div>
                        <img src="/images/finance2.jpg" class="hidden md:inline w-[60rem]" />
                    </div>
                    <div class="md:w-[40rem] flex flex-col space-y-3">
                        <h5 class="text-2xl font-bold">Asset Express</h5>
                        <div class="flex flex-col">
                            <h5 class="text-lg font-medium italic">Mission</h5>
                            <p class="text-justify">To empower individuals and businesses by providing strategic financial solutions that navigate the complexities of the modern economy. We specialize in real estate and chattel mortgages, bridge financing, and takeout loans, focusing on creating opportunities for growth and stability. Our mission is guided by a commitment to integrity, innovation, and personalized service, ensuring that every client achieves their financial goals with confidence.</p>
                        </div>
                        <div class="flex flex-col">
                            <h5 class=" text-lg font-medium italic">Vision</h5>
                            <p class="text-justify">Our vision is to become the most trusted and influential financial consultancy in the Philippines, known for our ability to deliver tailored solutions that meet the evolving needs of our clients. We aim to facilitate financial empowerment and economic growth for individuals and businesses alike, through expert advice, ethical practices, and a deep understanding of financial markets. By continuously adapting to the changing landscape and focusing on sustainable success, we aspire to make a lasting impact on the financial well-being of our clients and the community.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>
