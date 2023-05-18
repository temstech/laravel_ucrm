<script setup>
const props = defineProps({
    'data': Object,
})
</script>
<template>
    <div v-if="data.type === 'perDay' || data.type === 'perMonth' || data.type === 'perYear'" class="lg:w-2/3 w-full mx-auto mt-4 overflow-auto">
        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">日付</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">金額</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="item in data.data" :key="item.date">
                    <td class="border-t-2 border-gray-200 px-4 py-3">{{ item.date }}</td>
                    <td class="border-t-2 border-gray-200 px-4 py-3">{{ parseInt(item.total).toLocaleString() }}円</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div v-if="data.type === 'decile'" class="lg:w-2/3 w-full mx-auto mt-4 overflow-auto">
        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">グループ</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">平均</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">合計金額</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">構成比</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="item in data.data" :key="item.date">
                    <td class="border-t-2 border-gray-200 px-4 py-3">{{ item.decile }}</td>
                    <td class="border-t-2 border-gray-200 px-4 py-3">{{ parseInt(item.average).toLocaleString() }}円</td>
                    <td class="border-t-2 border-gray-200 px-4 py-3">{{ parseInt(item.totalPerGroup).toLocaleString() }}円</td>
                    <td class="border-t-2 border-gray-200 px-4 py-3">{{ item.totalRatio }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div v-if="data.type === 'rfm'" class="lg:w-2/3 w-full mx-auto mt-4 overflow-auto">
    <h2 class="text-center text-2xl">RFM 分析結果</h2>
    <div class="my-4 text-center">合計：{{ data.totals }} 人</div>
    <h3 class="text-center text-2xl my-4">RFMランク毎の人数</h3>
    <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">Rank</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">R</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">F</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">M</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="rfm in data.eachCount" :key="rfm.rank">
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rfm.rank }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rfm.r }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rfm.f }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rfm.m }}</td>
            </tr>
        </tbody>
    </table>
    <h3 v-if="data.rfmType === 'rf'" class="text-center text-2xl my-4">RとFの集計表</h3>
    <h3 v-if="data.rfmType === 'rm'" class="text-center text-2xl my-4">RとMの集計表</h3>
    <h3 v-if="data.rfmType === 'fm'" class="text-center text-2xl my-4">FとMの集計表</h3>
    <table v-if="data.rfmType === 'rf'" class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">rRank</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_5</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_4</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_3</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_2</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">f_1</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="rf in data.data" :key="rf.rRank">
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rf.rRank }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rf.f_5 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rf.f_4 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rf.f_3 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rf.f_2 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rf.f_1 }}</td>
            </tr>
        </tbody>
    </table>
    <table v-if="data.rfmType === 'rm'" class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">rRank</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_5</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_4</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_3</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_2</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_1</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="rm in data.data" :key="rm.rRank">
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rm.rRank }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rm.m_5 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rm.m_4 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rm.m_3 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rm.m_2 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ rm.m_1 }}</td>
            </tr>
        </tbody>
    </table>
    <table v-if="data.rfmType === 'fm'" class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
            <tr>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">fRank</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_5</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_4</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_3</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_2</th>
                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">m_1</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="fm in data.data" :key="fm.fRank">
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ fm.fRank }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ fm.m_5 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ fm.m_4 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ fm.m_3 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ fm.m_2 }}</td>
                <td class="border-t-2 border-gray-200 px-4 py-3">{{ fm.m_1 }}</td>
            </tr>
        </tbody>
    </table>
    </div>
</template>